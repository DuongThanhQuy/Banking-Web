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
        $userId = $_SESSION['UserId'];
        $status = $_SESSION['status'];
    } else {
        header("Location: index.php");
    }
    // die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Withdraw</title>
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
        </div>
        <h1 class="mb-2">WITHDRAW</h1>
        <?php
            if($status == 1) {
                $error = "";
                if(isset($_POST['idcard']) && isset($_POST['cvv']) && isset($_POST['endDate']) && isset($_POST['amount']) && isset($_POST['note'])) {
                    $idcard = $_POST['idcard'];
                    $cvv = $_POST['cvv'];
                    $endDate = $_POST['endDate'];
                    $amount = $_POST['amount'];
                    $note = $_POST['note'];
                    if(empty($idcard)) {
                        $error = "Vui lòng nhập số thẻ";
                    }
                    else if(empty($cvv)) {
                        $error = "Vui lòng nhập mã CVV";
                    }
                    else if(empty($endDate)) {
                        $error = "Vui lòng nhập ngày hết hạn";
                    }
                    else if(empty($amount)) {
                        $error = "Vui lòng nhập số tiền cần rút";
                    }
                    else if($amount < 0) {
                        $error = "Số tiền cần rút vào phải lớn hơn 0";
                    }
                    else if($amount%50000 != 0) {
                        $error = "Số tiền cần rút phải là bội số của 50000";
                    } else {
                        $wallet = new Wallet();
                        $result = $wallet->withdraw($userId, $idcard, $cvv, $endDate, $amount, $note);
                        $message = "";
                        if($result['code'] == 1) {
                            $error = $result['message'];
                        } else {
                            $message = $result['message'];
                        }
                    }
                }     
                ?>
                <form method="post" action="withdraw.php">
                    <div class="form-group mb-3">
                        <label for="phone" class="mr-2">Số thẻ</label>
                        <input  name="idcard" id="phone" type="text" class="form-control" placeholder="Số thẻ">
                    </div>

                    <div class="form-group mb-3">
                        <label for="cvv" class="mr-2">Mã CVV</label>
                        <input  name="cvv" id="cvv" type="text" class="form-control" placeholder="Mã CVV">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="end-date">Ngày hết hạn</label>
                        <input name="endDate" id="end-date" type="text" class="form-control" placeholder="yyyy-mmmm-dddd">
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount">Số tiền cần rút</label>
                        <input name="amount" id="amount" type="number" step="50000" value="0" class="form-control" placeholder="0">
                    </div>
                    <div class="form-group mb-3">
                        <label for="user">Ghi chú</label>
                        <textarea name="note" class="form-control" cols="30" rows="5" value=""></textarea>
                    </div>
                    <div>
                            <?php
                                if (!empty($error)) {
                                    echo "<p class='text-danger'>$error</p>";
                                }
                            ?>

                            <?php
                                if (!empty($message)) {
                                    echo "<p class='text-success'>$message</p>";
                                }
                            ?>
                        <button type="submit" class="btn btn-success px-5 mt-3 mr-2">Rút tiền</button>
                        <button type="reset" class="btn btn-success px-5 mt-3">Reset</button>
                    </div>
                </form>
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