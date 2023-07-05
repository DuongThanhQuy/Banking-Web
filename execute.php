<?php

    session_start();
    require_once('api/vendor/autoload.php');
    $error = "";
    if(isset($_SESSION['status'])) {
        if($_SESSION['status'] == -1) {
            header("Location: changePassword.php");
        }
        else if($_SESSION['status'] == 4) {
            header("Location: admin/index.php");
        }
    }
    else {
        header("Location: index.php");
    }
    if(isset($_POST['OTP']) && isset($_POST['phone']) && isset($_POST['amount']) && isset($_POST['note']) && isset($_POST['flag'])) {
        $otp = $_POST['OTP'];
        if (empty($otp)) {
            $error = "Please enter OTP";
        }
        $amount = $_POST['amount'];
        $name = $_POST['name'];
        $flag = $_POST['flag'];
        $note = $_POST['note'];
        $phone = $_POST['phone'];
        $receiveEmail = $_POST['receiveEmail'];
        $email = $_SESSION['User']['Email'];
        $wallet = new Wallet();
        $result = $wallet->confirmOTP($otp,$_SESSION['UserId'], $phone, $amount, $flag,$email, $receiveEmail,$name);
        print_r($result);
        if ($result['code'] == 0) {
            $error = $result['message'];
        } else {
            $error = $result['message'];
        }
        echo $error."<br>";
        echo "<a type='submit' href='transfer.php' class='btn btn-success px-5'>Back</a>";
    // } else {
    //     header("Location: transfer.php");
    }
?>