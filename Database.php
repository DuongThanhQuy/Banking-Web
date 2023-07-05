<?php
    class Database {
        private static $db;

        public function __construct()
        {
            
        }

        public function connect_database() {
            if (self::$db === NULL) {
                define('HOST','127.0.0.1');
                define('USER','root');
                define('PASS','');
                define('DB','onlinebanking');
                self::$db = new mysqli(HOST, USER, PASS, DB);
            }
            mysqli_set_charset(self::$db, 'UTF8');
            return self::$db;
        }

        public function query($sql) {
            $result = $this->connect_database()->query($sql);
            if(!$result) {
                return array('code'==1, 'error' => $this->db->error);
            }
            $data = array();
            while ($item = $result->fetch_assoc()) {
                array_push($data, $item);
            }
            return array('code' => 0, 'data' => $data);
        }

        public function query_prepared($sql, $params) {
            $stm = $this->connect_database()->prepare($sql);
            $tmp = array();
            foreach($params as $key => $value) $tmp[$key] = &$params[$key];
            call_user_func_array(array($stm, 'bind_param'), $tmp);

            if (!$stm->execute()) {
                return array('code'==1, 'error' => $this->db->error);
            }
            $result = $stm->get_result();
            return array('code' => 0, 'data' => $result);
        }
    }
?>