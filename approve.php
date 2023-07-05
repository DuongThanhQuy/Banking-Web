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
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $wallet = new Wallet();
        $result = $wallet->approveTrans($id);
        if($result['code'] == 1) {
            die($result['Message']);
        } else {
            header("Location: verify-transaction.php");
        }
    }else {
        die("Lack of information");
    }
?>