<?php
    require_once('api/vendor/autoload.php');
    if(isset($_POST['OTP']) && isset($_POST['email'])) {
        $otp = $_POST['OTP'];
        $email = $_POST['email'];
        if (empty($otp) or empty($email)) {
            die("Please enter OTP or email");
        }
        $account = new Account();
        $result = $account->confirmOTP($otp,$email);
        if ($result['code'] == 0) {
            header("Location: changePassword.php");
        } else {
           echo $result['message'];
        }
    } else {
        header("Location: forgot-password.php");
    }
?>

