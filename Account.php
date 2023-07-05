<?php
        
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require_once('vendor/autoload.php');
    class Account {
        private $db;
        public function __construct()
        {
            $this->db = new Database();
        }

        // For user
        function login($username, $password) {
            if ($username == "admin" && $password == "admin") {
                $admin = array('username' => $username, 'password' => $password,'status' => 4);
                return array('code' => 4, 'message' => 'Đăng nhập bởi Admin', 'data' => $admin);
            }
    
            $sql = "SELECT * FROM Account WHERE username = ?";
            $param = array('s',$username);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            } else if ($result['data']->num_rows== 0) {
                return array('code' => -2, 'message' => 'Tài khoản không tồn tại');
            }
    
            $data = $result['data']->fetch_assoc();
            $hashed_password = $data['password'];
            $status = $data['status'];
            $userId = $data['UserID'];

            $sql = "SELECT * FROM InvalidAccess Where UserId = ?";
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $invlAccess = $result['data'];
            if ($invlAccess->num_rows != 0) {
                $retry = $invlAccess->fetch_assoc()['retry'];
                $expire = intval($retry) - time();
                if($expire <= 60 && $expire >= 0) {
                    return array('code' => -2, 'message' => 'Vui lòng thử lại sau 1 phút');
                } 
            }

            if(!password_verify($password, $hashed_password)) {
                return $this->checkInvalidAccess($userId);
            } else if ($status == -2) {
                return array('code' => $status,'message' => 'Tài khoản của bạn đã bị khóa');
            } else if ($status == -1) {
                $sql = "UPDATE InvalidAccess SET invalid = ?, flag = ? WHERE UserId = ?";
                $inl = 0;
                $flag = 0;
                $param = array('iii',$inl,$flag,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => $status,'message' => 'Tài khoản đăng nhập lần đầu', 'data' => $data);
            }
            else if($status == 0) {
                $sql = "UPDATE InvalidAccess SET invalid = ?, flag = ? WHERE UserId = ?";
                $inl = 0;
                $flag = 0;
                $param = array('iii',$inl,$flag,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => $status, 'message' => 'Tài khoản này chưa được xác minh', 'data' => $data);
            } else {
                $sql = "UPDATE InvalidAccess SET invalid = ?, flag = ? WHERE UserId = ?";
                $inl = 0;
                $flag = 0;
                $param = array('iii',$inl,$flag,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => $status, 'message' => 'Đăng nhập thành công','data' => $data);
            }
        }

        function register($name, $birthday, $address, $email, $phone, $front_Id, $back_Id) {
            if($this->is_email_exists($email)) {
                return array('code' => 1, 'message' => 'Email đã tồn tại');
            }
            $sql = "INSERT INTO UserInfo(FullName, BirthDay, UserAddress, Phone, Email, Front_IdentityCard, Back_IndentityCard) values(?,?,?,?,?,?,?)";
            $param = array('sssssss',$name,$birthday,$address,$phone,$email,$front_Id,$back_Id);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            
            // Select UserID to create a Account
            $sql = "SELECT UserId from UserInfo WHERE email = ?";
            $param = array("s",$email);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
    
            $data = $result['data']->fetch_assoc();
            $userId = $data['UserId'];
    
            // Create random username
            $username = (string)rand(1000000000,9999999999);
            // Create random password
            $password = (string)rand(100000,999999);
            $status = -1;
            $active_token = md5($username);
            $account = $this->createAccount($username,$password,$userId,$status,$active_token);
    
            // Send account to user email;
            $subject = "Create account successfully";
            $message = "Your account, username: " . $username . ", password: " . $password;
            $this->sendEmail($email, $message, $subject);
            return array('code' => 0, 'message' => 'Register successfully');
        }

        private function createAccount($username,$password,$userId,$status,$active_token) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            $sql = "INSERT INTO Account(username, password, UserID, status, activate_token) VALUES(?,?,?,?,?)";
            $param = array('ssiis',$username,$hashed_password,$userId,$status,$active_token);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
    
            return array('code'=> 0, 'message' => 'Tạo tài khoản thành công', 'Username' => $username, 'Password' => $password);
        }

        private function checkInvalidAccess($userId) {
            // Tìm trong bảng InvalidAccess để ghi nhận lần đăng nhập bất thường
            $sql = "SELECT * FROM InvalidAccess Where UserId = ?";
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $invlAccess = $result['data'];
    
            // Nếu user chưa có trong InvalidAccess
            if ($invlAccess->num_rows == 0) {
                $sql = "INSERT INTO InvalidAccess(UserId, invalid) VALUES(?,?)";
                $invalid = 1;
                $param = array('ii',$userId,$invalid);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => -2,'message' => 'Mật khẩu không đúng');
            }

            $invTable = $invlAccess->fetch_assoc();
            $invalid = $invTable['invalid'];
            $flag = $invTable['flag'];
            // Nếu đã ghi nhận những lần đăng nhập bất thường thì tăng lên 1
            if($invalid <2) {
                $sql = "UPDATE InvalidAccess SET invalid = ? WHERE UserId = ?";
                $invalid = $invalid + 1;
                $param = array('ii',$invalid,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => -2,'message' => 'Mật khẩu không đúng');
            }
            // Nếu User đã nhập sai mật khẩu 3 lần thì cập nhật 1 lần đăng nhập bất thường 
            else if ($invalid==2 && $flag==0){
                $sql = "UPDATE InvalidAccess SET invalid = ?, flag = ?, retry = ? WHERE UserId = ?";
                $invalid = 0;
                $flag = 1;
                $retry = time() + 60;
                $param = array('iiii',$invalid,$flag,$retry,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => -2,'message' => 'Vui lòng thử lại sau 1 phút');
            }
    
            // Nếu User đã nhập sai mật khẩu 3 lần và đã ghi nhận 1 trường hợp bất thường thì vô hiệu hóa tài khoản
            else if ($invalid==2 && $flag==1){
                $sql = "UPDATE Account SET status = ? WHERE UserId = ?";
                $st = -2;
                $param = array('ii',$st,$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                $sql = "UPDATE InvalidAccess SET day_block = CURDATE() WHERE UserId = ?";
                $param = array('i',$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                return array('code' => -2,'message' => 'Tài khoản của bạn đã bị khóa');
            }
        }

        function changePasswordFirstLogin($userId, $newPassword) {
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
    
            $sql = "UPDATE Account SET password = ?, status = ? WHERE UserID = ?";
            $status = 0;
            $param = array('sii',$hashed_password,$status,$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }

            return array('code' => 0, 'message' => 'Đổi mật khẩu thành công');
        }

        function changePassword($oldPassword,$newPassword, $userId) {
            $sql = "SELECT password FROM Account WHERE UserID = ?";
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            
            $user = $result['data']->fetch_assoc();
            $hashed_password = $user['password'];
            if(!password_verify($oldPassword, $hashed_password)) {
                return array('code' => 1, 'message' => 'Mật khẩu cũ không đúng');
            }
    
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE Account SET password = ? where UserId = ?";
            $param = array('si',$hashed_password,$userId);
            
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
    
            return array('code' => 0, 'message' => 'Đổi mật khẩu thành công');
        }

        function forgotPassword($email) {
            if($this->is_email_exists($email)) {
    
                $sql = "SELECT * FROM reset_token WHERE email = ?";
                $param = array('s',$email);
                $result = $this->db->query_prepared($sql, $param);

                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                $otp = rand(100000,999999);
                $exp = time() + 60; // hết hạn sau 1 phút
                // Nếu chưa tồn tại email trong bảng reset_token thì thêm vào bảng
                if($result['data']->num_rows == 0) {
                    $sql = "INSERT INTO reset_token VALUES (?, ?, ?)";
                    $param = array('sii',$email,$otp,$exp);
                    $result = $this->db->query_prepared($sql, $param);
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                }else {
                    $sql = "UPDATE reset_token SET otp = ?, expire_on = ? WHERE email = ?";
                    $param = array('iis',$otp,$exp,$email);
                    $result = $this->db->query_prepared($sql, $param);
    
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                }

                $subject = "Quên mật khẩu";
                $message = "Mã OTP: " . $otp . ". Hết hạn sau 1 phút";
                $this->sendEmail($email,$message,$subject);
                return array('code' => 0, 'message' => 'Kiểm tra email của bạn');
            } else {
                return array('code' => 1, 'message' => 'Email không đúng');
            }
        }

        function confirmOTP($confirmOtp, $email) {
            if($this->is_email_exists($email)) {
                $sql = "SELECT otp, expire_on FROM reset_token WHERE email = ?";
                $param = array("s",$email);
                $result = $this->db->query_prepared($sql, $param);
    
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
    
                $data = $result['data']->fetch_assoc();
                $otp = $data['otp'];
                $exp = $data['expire_on'];

                if((time() - intval($exp)) > 60) {
                    return array('code' => 1, 'message' => 'Mã xác thực hết hạn');
                } else {
                    if ($confirmOtp != $otp) {
                        return array('code' => 1, 'message' => 'Mã xác thực OTP không đúng');
                    } else {
                        return array('code' => 0, 'message' => 'Xác thực OTP thành công');
                    }
                }
            } else {
                return array('code' => 1, 'message' => 'Email không đúng');
            }
        }

        public function getUserInfo($userId) {
            $sql = "SELECT * FROM UserInfo WHERE UserID = ?";
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data']->fetch_assoc();
            return array('code'=> 1, 'data' => $data);
        }

        function is_email_exists($email) {
            $sql = "SELECT * FROM UserInfo Where email = ?";
            $param = array('s',$email);
            $result = $this->db->query_prepared($sql, $param);

            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
    
            if ($data->num_rows > 0) {
                return true;
            }
            return false;
        }
    
        function sendEmail($email, $message, $subject) {
            $mail = new PHPMailer(true);
    
            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->CharSet = 'UTF-8';
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'gianguyen.2002.gn@gmail.com';                     //SMTP username
                $mail->Password   = 'xyychwyndyvfdncp';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
                //Recipients
                $mail->setFrom('gianguyen.2002.gn@gmail.com', 'Admin');
                $mail->addAddress($email, 'Người nhận');     //Add a recipient
                // $mail->SMTPDebug = 2;
                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;
    
                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        // For Admin to take data

        function getAllAccount() {
            $sql = "SELECT * FROM Account ORDER BY(day_create) DESC";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getAccountActive() {
            $sql = "SELECT * FROM Account WHERE status = 1";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getAccountToActive() {
            $sql = "SELECT DISTINCT Account.* FROM Account,UserInfo WHERE Account.status = 0 or Account.status = 3 ORDER BY(UserInfo.Front_IdentityCard) ASC";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getAccountDisable() {
            $sql = "SELECT * FROM Account WHERE status = -2 and UserId IN(SELECT UserId FROM invalidaccess WHERE invalid = 0) or status = -2 and UserId NOT IN(SELECT UserId FROM invalidaccess WHERE invalid = 0)";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getAccountDisableForIvl() {
            $sql = "SELECT * FROM Account WHERE status = -2 and UserId IN(SELECT UserId FROM invalidaccess WHERE invalid = 2 and flag = 1 ORDER BY(day_block) DESC) ";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getAccountDailyCreate() {
            $sql = "SELECT * FROM Account WHERE day_create = CURDATE()";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }
        
        function updateAccount($userId, $st) {
            $sql = "UPDATE Account SET status = ? WHERE UserId = ?";
            $param = array('ii',$st,$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            return array('code' => 0,'message' => 'Successful');
        }
    }
    // $acc = new Account();
    // print_r($acc->getAccountActive());
?>