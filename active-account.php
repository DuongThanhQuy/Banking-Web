<?php
    session_start();
    require_once('../api/vendor/autoload.php');
    if(isset($_SESSION['status'])) {
        if($_SESSION['status'] != 4) {
          header("Location: ../index.php");
        }
    } else {
        header("Location: ../index.php");
    }
    if(isset($_POST['action']) && isset($_POST['userId'])) {
        $action = $_POST['action'];
        $userId = $_POST['userId'];
        $acc = new Account();
        if($action == 'active') {
            $st = 1;
            $result = $acc->updateAccount($userId,$st);
            if($result['code'] == 1) {
                die($result['Message']);
            } else {
                header("Location: index.php");
            }
        } else if($action == 'update') {
            $st = 2;
            $result = $acc->updateAccount($userId,$st);
            if($result['code'] == 1) {
                die($result['Message']);
            } else {
                header("Location: index.php");
            }
        } else {
            $st = -2;
            $result = $acc->updateAccount($userId,$st);
            if($result['code'] == 1) {
                die($result['Message']);
            } else {
                header("Location: index.php");
            }
        }
    } else {
        die("Lack of information");
    }

?>