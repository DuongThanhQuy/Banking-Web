<?php
    session_start();
    require_once('api/vendor/autoload.php');
    if(isset($_SESSION['status'])) {
        if($_SESSION['status'] == -1) {
          header("Location: changePassword.php");
        }
        else if($_SESSION['status'] == 4) {
          header("Location: admin/index.php");
        }
    }
    if(isset($_SESSION['User'])) {
        $UserInfo = $_SESSION['User'];
        // print_r($UserInfo);
        $status = $_SESSION['status'];
        $accountStatus = array(0 => 'Chờ xác minh', 1 => 'Đã xác minh', 2 => 'Yêu cầu bổ sung thông tin', 3 => 'Chờ cập nhật');
    } else {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>My Account</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <!-- <link href="app/views/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Template Main CSS File -->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require_once('inc/header.php'); ?>
    <div class="container my-4">
        <div class="row justify-content-end">
        <a class="text-white btn btn-success" href="reset-password.php">Change Password</a>
        </div>
        <h1>User Information</h1>
        <table class="table">
            <tr class="header">
                <td>Name</td>
                <td>Phone</td>
                <td>Email</td>
                <td>Birthday</td>
                <td>Address</td>
                <td>Status</td>
            </tr>
        <?php
            $name = $UserInfo['FullName'];
            $phone = $UserInfo['Phone'];
            $email = $UserInfo['Email'];
            $birthday = $UserInfo['BirthDay'];
            $address = $UserInfo['UserAddress'];
            $st = $accountStatus[$status];
        ?>
            <tr class="item">
                <td><?= $name ?></td>
                <td><?= $phone ?></td>
                <td><?= $email ?></td>
                <td><?= $birthday ?></td>
                <td><?= $address ?></td>
                <td><?= $st ?></td>
            </tr>
        </table>
        <?php
            if($status == 1) {
                $wallet = new Wallet();
                $userWallet = $wallet->getWallet($_SESSION['UserId']);
                $amount = $userWallet['Balance'];
                ?>
                <div class="row justify-content-end">
                    <p class="mr-2">Số dư tài khoản</p>
                    <p><?=$amount?></p>
                </div>
            <?php
            } else {
                ?>
                <div class="row justify-content-end">
                    <p>Ví tiền chưa được kích hoạt, vui lòng chờ xác minh tài khoản</p>
                </div>
            <?php
            }
        ?>
    </div>
    <?php
    require_once("inc/service.php");
    require_once("inc/contact.php");
    require_once("inc/footer.php");
    require_once("inc/footer.php");
  ?>

  <!-- My Coding End Here -->


  <!-- Vendor JS Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>


  <!-- Template Main JS File -->
  <script src="main.js"></script>

</body>
</html>