<?php
    require_once('vendor/autoload.php');
    class Wallet {
        private $db;
        public function __construct()
        {
            $this->db = new Database();
        }

        function getWallet($userId) {
            $sql = "SELECT * FROM Wallet WHERE UserId = ?";       
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            if($result['data']->num_rows== 0) {
                $sql = "SELECT Phone,email FROM UserInfo WHERE UserID = ?";
                $param = array('i',$userId);
                $result = $this->db->query_prepared($sql, $param);
                $data =  $result['data']->fetch_assoc();
                $phone = $data['Phone'];
                $email = $data['email'];
                $sql = "INSERT INTO Wallet(UserId, Phone, email) VALUES(?,?,?)";
                $param = array('iss',$userId,$phone,$email);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                $sql = "SELECT * FROM Wallet WHERE UserId = ?";       
                $param = array('i',$userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => -2, 'message' => 'Execute Failed');
                }
                $data = $result['data']->fetch_assoc();
                return $data;
            }
            $data = $result['data']->fetch_assoc();
            return $data;
        }

        function deposit($userId, $idcard, $cvv, $endDate, $amount) {
            $sql = "SELECT * FROM CreditCard WHERE IDCard = ?";
            $param = array('i',$idcard);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            if($result['data']->num_rows== 0) {
                return array('code' => 1, 'message' => 'Thẻ này không được hỗ trợ');
            }
            $data = $result['data']->fetch_assoc();
            $ID = $data['IDCard'];
            $CVV = $data['CVV'];
            $END = $data['endDate'];
            if($CVV != $cvv) {
                return array('code' => 1, 'message' => 'Mã CVV không đúng');
            }
            if($END != $endDate) {
                return array('code' => 1, 'message' => 'Ngày hết hạn không đúng');
            }
            if(date("Y-m-d") > $END) {
                return array('code' => 1, 'message' => 'Thẻ đã hết hạn');
            }

            if($idcard == '111111') {
                $sql = "UPDATE Wallet SET Balance = Balance + ? WHERE UserId = ?";
                $param = array('ii',$amount, $userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                $sql = "INSERT INTO HistoryTransaction(UserId,Amount,DateTrans,TypeTrans,Status) VALUES(?,?,CURDATE(),'Deposit',?)";
                $st = 1;
                $param = array('iii',$userId,$amount,$st);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                return array('code' => 0, 'message' => "Nạp thành công $amount VND vào tài khoản");
            }
            if($idcard == '222222') {
                if($amount > 1000000){
                    return array('code' => 1, 'message' => 'Thẻ này chỉ hỗ trợ nạp tiền dưới 1,000,000 trên 1 lần nạp');
                }
                $sql = "UPDATE Wallet SET Balance = Balance + ? WHERE UserId = ?";
                $param = array('ii',$userId, $amount);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                $sql = "INSERT INTO HistoryTransaction(UserId,Amount,DateTrans,TypeTrans,Status) VALUES(?,?,CURDATE(),'Deposit',?)";
                $st = 1;
                $param = $array('iii',$userId,$amount,$st);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                return array('code' => 0, 'message' => "Nạp thành công $amount VND vào tài khoản");
            }
            if($idcard == '333333') {
                return array('code' => 1, 'message' => "Thẻ đã hết tiền, vui lòng chọn thẻ khác");
            }
            return array('code' => 0, 'message' => "Nạp tiền thành công");;
        }

        function withdraw($userId, $idcard, $cvv, $endDate, $amount, $note) {
            $sql = "SELECT COUNT(*) FROM HistoryTransaction WHERE UserId = ? and TypeTrans = ?";
            $type = "Withdraw";
            $param = array('is',$amount,$type);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $count = $result['data']->fetch_assoc();
            if($count['COUNT(*)'] >= 2) {
                return array('code' => 1, 'message' => "Mỗi ngày chỉ được rút tối đa 2 lần");
            }

            $sql = "SELECT * FROM CreditCard WHERE IDCard = ?";
            $param = array('i',$idcard);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            if($result['data']->num_rows== 0) {
                return array('code' => 1, 'message' => 'Thẻ này không được hỗ trợ để rút tiền');
            }
            $data = $result['data']->fetch_assoc();
            $ID = $data['IDCard'];
            $CVV = $data['CVV'];
            $END = $data['endDate'];
            if($idcard == '111111' && $ID == $idcard) {
                if($CVV != $cvv) {
                    return array('code' => 1, 'message' => 'Mã CVV không đúng');
                }
                if($END != $endDate) {
                    return array('code' => 1, 'message' => 'Ngày hết hạn không đúng');
                }
                if(date("Y-m-d") > $END) {
                    return array('code' => 1, 'message' => 'Thẻ đã hết hạn');
                }

                $wallet = new Wallet();
                $userWallet = $wallet->getWallet($userId);
                $balance = $userWallet['Balance'];
                $cost = 0.05 * floatval($amount);
                if ($amount + $cost <= $balance) {
                    if($amount <= 5000000) {
                        $sql = "UPDATE Wallet SET Balance = Balance - ? WHERE UserId = ?";
                        $withdraw = $amount + $cost;
                        $param = array('ii',$withdraw, $userId);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                        $sql = "INSERT INTO HistoryTransaction(UserId,Amount,DateTrans,TypeTrans,Status) VALUES(?,?,CURDATE(),'Withdraw',?)";
                        $st = 1;
                        $param = array('iii',$userId,$amount,$st);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                        return array('code' => 0, 'message' => "Rút thành công $amount VND");
                    } else {
                        $sql = "INSERT INTO HistoryTransaction(Amount,DateTrans,TypeTrans,Status) VALUES(?,CURDATE(),'Withdraw',?)";
                        $st = 0;
                        $param = array('ii',$amount,$st);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                        return array('code' => 0, 'message' => "Đang chờ phê duyệt bởi admin");
                    }
                } else {
                    return array('code' => 1, 'message' => 'Số dư tài khoản không đủ');
                }

            } else {
                return array('code' => 1, 'message' => 'Thẻ này không được hỗ trợ để rút tiền');
            }

        }

        function transfer_prepared($userId, $phone, $amount, $flag, $note) {
            $user = new User();
            $getUser = $user->getEmailUserByPhone($phone);
            if($getUser['code']==1) {
                $getUser['data']['amount'] = $amount;
                $getUser['data']['flag'] = $flag;
                $getUser['data']['note'] = $note;
                return $getUser;
            } else {
                return $getUser;
            }
        }

        function sendOTP($email) {
            $acc = new Account();
            if($acc->is_email_exists($email)) {
                $sql = "SELECT * FROM TransferOTP WHERE email = ?";
                $param = array('s',$email);
                $result = $this->db->query_prepared($sql, $param);

                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                $otp = rand(100000,999999);
                $exp = time() + 60; // hết hạn sau 1 phút
                // Nếu chưa tồn tại email trong bảng reset_token thì thêm vào bảng
                if($result['data']->num_rows == 0) {
                    $sql = "INSERT INTO TransferOTP VALUES (?, ?, ?)";
                    $param = array('sii',$email,$exp,$otp);
                    $result = $this->db->query_prepared($sql, $param);
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                } else {
                    $sql = "UPDATE TransferOTP SET otp = ?, expire_on = ? WHERE email = ?";
                    $param = array('iis',$otp,$exp,$email);
                    $result = $this->db->query_prepared($sql, $param);
    
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                }

                $subject = "Chuyển tiền";
                $message = "Mã OTP: " . $otp . ". Hết hạn sau 1 phút";
                $acc->sendEmail($email,$message,$subject);
                return array('code' => 0, 'message' => 'Kiểm tra email của bạn');
            } else {
                return array('code' => 1, 'message' => 'Email không đúng');
            }
        }

        function confirmOTP($confirmOtp,$userId, $phone, $amount, $flag, $email,$receiveEmail, $name) {
            $acc = new Account();
            if($acc->is_email_exists($email)) {
                $sql = "SELECT otp, expire_on FROM TransferOTP WHERE email = ?";
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
                    } else 
                    {
                        // return array('code' => 0, 'message' => 'Xác thực OTP thành công');
                        return $this->transfer($userId,$phone,$amount,$flag, $receiveEmail, $name);
                    }
                }
            } else {
                return array('code' => 1, 'message' => 'Email không đúng');
            }
        }

        function transfer($userId, $phone, $amount, $flag, $receiveEmail, $name) {
            $wallet = new Wallet();
            $acc = new Account();
            $userWallet = $wallet->getWallet($userId);
            $balance = $userWallet['Balance'];
            $cost = 0.05 * floatval($amount);
            if ($amount + $cost <= $balance) {
                if($amount <= 5000000) {
                    if($flag == "user1") {
                        $sql = "UPDATE Wallet SET Balance = Balance - ? WHERE userId = ?";
                        $withdraw = $amount + $cost;
                        $param = array('ii',$withdraw, $userId);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                        $sql = "UPDATE Wallet SET Balance = Balance + ? WHERE Phone = ?";
                        $param = array('is',$amount, $phone);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                    } else {
                        $sql = "UPDATE Wallet SET Balance = Balance - ? WHERE userId = ?";
                        $withdraw = $amount + $cost;
                        $param = array('ii',$amount, $userId);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                        $sql = "UPDATE Wallet SET Balance = Balance + ? WHERE Phone = ?";
                        $param = array('is',$withdraw, $phone);
                        $result = $this->db->query_prepared($sql, $param);
                        if($result['code']==1) {
                            return array('code' => 1, 'message' => 'Execute Failed');
                        }
                    }
                    $sql = "INSERT INTO HistoryTransaction(UserId,Amount,DateTrans,TypeTrans,Status) VALUES(?,?,CURDATE(),'Transfer',?)";
                    $st = 1;
                    $param = array('iii',$userId,$amount,$st);
                    $result = $this->db->query_prepared($sql, $param);
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                    $subject = "Thông báo";
                    $message = "Bạn đã nhận được: " . $amount . " từ ". $name;
                    $acc->sendEmail($receiveEmail,$message,$subject);
                    return array('code' => 0, 'message' => "Chuyển tiền thành công");
                } else {
                    $sql = "INSERT INTO HistoryTransaction(Amount,DateTrans,TypeTrans,Status) VALUES(?,CURDATE(),'Transfer',?)";
                    $st = 0;
                    $param = array('ii',$amount,$st);
                    $result = $this->db->query_prepared($sql, $param);
                    if($result['code']==1) {
                        return array('code' => 1, 'message' => 'Execute Failed');
                    }
                    return array('code' => 0, 'message' => "Đang chờ phê duyệt bởi admin");
                }
            } else {
                return array('code' => 1, 'message' => 'Số dư tài khoản không đủ');
            }
        }

        function getTransHistory($userId) {
            $sql = "SELECT * FROM HistoryTransaction WHERE UserId = ?";
            $param = array('i',$userId);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $data = array();
            while($rows = $result['data']->fetch_assoc()) {
                array_push($data,$rows);
            }
            return array('code' => 1, 'data' => $data);
        }

        function getAllTransHistory() {
            $sql = "SELECT * FROM HistoryTransaction ORDER BY(DateTrans) DESC";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return array('code' => 1, 'data' => $data);
        }
        // For Admin

        function getWithDraw() {
            $sql = "SELECT * FROM HistoryTransaction WHERE TypeTrans = 'Withdraw' and Status = 0  ORDER BY(DateTrans) DESC";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return array('code' => 1, 'data' => $data);
        }
        function getTransfer() {
            $sql = "SELECT * FROM HistoryTransaction WHERE TypeTrans = 'Transfer' and Status = 0  ORDER BY(DateTrans) DESC";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return array('code' => 1, 'data' => $data);
        }

        function approveTrans($id) {
            $sql = "SELECT * FROM HistoryTransaction WHERE ID = $id";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => 1, 'message' => 'Execute Failed');
            }
            $data = $result['data'][0];
            $userId = $data['UserId'];
            $amount = $data['Amount'];
            $type = $data['TypeTrans'];
            $cost = $amount * 0.05;
            if($type == "Withdraw") {
                $sql = "UPDATE Wallet SET Balance = Balance - ? WHERE UserId = ?";
                $withdraw = $amount + $cost;
                $param = array('ii',$withdraw, $userId);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }

                $sql = "UPDATE HistoryTransaction SET Status = ? WHERE ID = ?";
                $st = 1;
                $param = array('ii',$st, $id);
                $result = $this->db->query_prepared($sql, $param);
                if($result['code']==1) {
                    return array('code' => 1, 'message' => 'Execute Failed');
                }
                return array('code'=> 0, "message"=> "Phê duyệt thành công");
            }
            return array('code'=> 1, "message"=> "Chỉ hỗ trợ chức năng rút tiền");
        }
    }
?>