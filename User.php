<?php
    require_once('vendor/autoload.php');
    class User {
        private $db;
        public function __construct()
        {
            $this->db = new Database();
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

        public function getAllUser() {
            $sql = "SELECT * FROM UserInfo";
            $result = $this->db->query($sql);
            if($result['code']==1) {
                return array('code' => -2, 'message' => 'Execute Failed');
            }
            $data = $result['data'];
            return $data;
        }

        function getEmailUserByPhone($phone) {
            $sql = "SELECT UserId,Email,FullName FROM UserInfo WHERE Phone = ?";
            $param = array('s',$phone);
            $result = $this->db->query_prepared($sql, $param);
            if($result['code']==1) {
                return array('code' => 0, 'message' => 'Execute Failed');
            }
            if($result['data']->num_rows != 0) {
                $data = $result['data']->fetch_assoc();
                return array('code'=> 1, 'data' => $data);
            } else {
                return array('code' => 0, 'message'=>'Người dùng không tồn tại');
            }
        }

    }

    $user = new User();
    // echo $user->is_email_exists("gianguyen.2002.gn@gmail.com");
    // print_r($user->getEmailUserByPhone('1'));
?>