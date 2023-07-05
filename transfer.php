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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Transfer</title>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Template Main CSS File -->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require_once('inc/header.php'); ?>
    <div class="container my-4">
        <div class="row justify-content-end">
        </div>
        <h1 class="mb-2">TRANSFER</h1>
        <?php
            if($status == 1) {
                $error = "";
                $phone = "";
                $amount = 0;
                $note = "";
                if(isset($_POST['phone']) && isset($_POST['amount']) && isset($_POST['note']) && isset($_POST['flag'])) {
                    $phone = $_POST['phone'];
                    $amount = $_POST['amount'];
                    $note = $_POST['note'];
                    $flag = $_POST['flag'];
                    if(empty($phone)) {
                        $error = "Vui lòng nhập số điện thoại người nhận";
                    } else if($phone == $_SESSION['User']['Phone']) {
                        $error = "Số điện thoại không hợp lệ";
                    }
                    else if(empty($amount)) {
                        $error = "Vui lòng nhập số tiền cần chuyển";
                    }
                    else if($amount < 0) {
                        $error = "Số tiền cần chuyển vào phải lớn hơn 0";
                    }
                    else if(empty($flag)) {
                        $error = "Vui lòng chọn người chịu phí chuyển";
                    } else {
                        $wallet = new Wallet();
                        $result = $wallet->transfer_prepared($userId, $phone, $amount, $flag, $note);
                        if($result['code'] == 0) {
                            $error = $result['message'];
                        }
                    }
                }     
                ?>
                <form method="post" action="confirm.php">
                    <div class="form-group mb-3">
                        <label for="phone" class="mr-2">Số điện thoại người nhận</label>
                        <input  name="phone" id="phone" type="text" class="form-control" value="<?=$phone?>" >
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Số tiền cần chuyển</label>
                        <input name="amount" type="number" step="1000" value="<?=$amount?>" class="form-control" placeholder="0">
                    </div>
                    <label class="form-check-label mr-3">Phí chuyển:</label><br>
                    <div class="form-check form-check-inline">
                        <input name="flag" id="user1" type="radio" class="form-check-input" value="user1" checked>
                        <label class="form-check-label mr-2" for="user1">Người chuyển</label>
                        <input name="flag" id="user2" type="radio" class="form-check-input" value="user2">
                        <label class="form-check-label" for="user2">Người nhận</label>
                    </div>
                    <div class="form-group mb-3">
                        <label for="user">Ghi chú</label>
                        <textarea name="note" class="form-control" cols="30" rows="5" value="<?=$note?>"></textarea>
                    </div>
                    <div>
                            <?php
                                if (!empty($error)) {
                                    echo "<p class='text-danger'>$error</p>";
                                }
                            ?>
                        <button type="submit" class="btn btn-success px-5 mt-3 mr-2">Xác nhận</button>
                        <button type="reset" class="btn btn-success px-5 mt-3">Reset</button>
                    </div>
                </form>

                <?php
                        if(isset($result)){
                            if($result['code']==1) {
                                $data = $result['data'];
                                $tmp = array('user1'=>'Người chuyển', 'user2' => 'Người nhận');
                                ?>
                                <form method="post" action="confirm.php" class="mt-3">
                                    <p>Số điện thoại người nhận: <strong><?=$phone?></strong></p>
                                    <input type="hidden" name="userReceive" value="<?=$data['UserId']?>">
                                    <input type="hidden" name="amount" value="<?=$data['amount']?>">
                                    <input type="hidden" name="flag" value="<?=$data['flag']?>">
                                    <input type="hidden" name="note" value="<?=$data['note']?>">
                                    <input type="hidden" name="receiveEmail" value="<?=$data['Email']?>">
                                    <input type="hidden" name="phone" value="<?=$phone?>">
                                    <p>Tên người nhận: <strong><?=$data['FullName']?></strong></p>
                                    <p>Số tiền: <strong><?=$data['amount']?></strong></p>
                                    <p>Người chịu phí chuyển: <strong><?=$tmp[$data['flag']]?></strong></p>
                                    <p>Ghi chú:</p>
                                    <p><strong><?=$data['note']?></strong></p>
                                    <button type="submit" class="btn btn-success px-5 mt-3 mr-2">Chuyển tiền</button>
                                    <a href="transfer.php" class="btn btn-success px-5 mt-3">Hủy</a>
                                </form>
                                <?php
                            }
                        }
                ?>
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
<!-- Button to Open the Modal -->

<!-- The Modal -->

  <!-- Vendor JS Files -->
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