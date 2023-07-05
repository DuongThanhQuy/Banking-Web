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

    if(isset($_SESSION['User'])) {
        $userId = $_SESSION['UserId'];
        if(isset($_POST['phone']) && isset($_POST['amount']) && isset($_POST['note']) && isset($_POST['flag'])) {
            $phone = $_POST['phone'];
            $amount = $_POST['amount'];
            $note = $_POST['note'];
            $flag = $_POST['flag'];
            $wallet = new Wallet();
            $confirm = $wallet->transfer_prepared($userId, $phone, $amount, $flag, $note);
            if($confirm['code'] == 0) {
                $error = $confirm['message'];
            }
            if(isset($_POST['Email'])) {
                $email = $_POST['Email'];
                $result = $wallet->sendOTP($email);
                if($result['code'] == 1) {
                    $error = $result['message'];
                }
            }
        }
    } else {
        header("Location: index.php");
    }
?>
<DOCTYPE html>
    <html lang="en">
    <head>
        <title>Confirm Transfer</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="container">
        <div class="row justify-content-center">
        <?php
            if(isset($confirm)){
                if($confirm['code']==1) {
                    $data = $confirm['data'];
                    $receiveEmail = $data['Email'];
                    $name = $data['FullName'];
                    $tmp = array('user1'=>'Người chuyển', 'user2' => 'Người nhận');
                    ?>
                    <div class="col-md-6 col-lg-7 col-sm-12 border mt-4">
                    <h3 class="text-center text-secondary mt-2">Kiểm tra thông tin</h3>
                        <form method="post" action="confirm.php" class="mt-3">
                            <p>Số điện thoại người nhận: <strong><?=$phone?></strong></p>
                            <input type="hidden" name="userReceive" value="<?=$data['UserId']?>">
                            <input type="hidden" name="amount" value="<?=$data['amount']?>">
                            <input type="hidden" name="flag" value="<?=$data['flag']?>">
                            <input type="hidden" name="note" value="<?=$data['note']?>">
                            <input type="hidden" name="receiveEmail" value="<?=$data['Email']?>">
                            <input type="hidden" name="phone" value="<?=$phone?>">
                            <input type="hidden" name="Email" value="<?=$_SESSION['User']['Email']?>">
                            <p>Tên người nhận: <strong><?=$data['FullName']?></strong></p>
                            <p>Số tiền: <strong><?=$data['amount']?></strong></p>
                            <p>Người chịu phí chuyển: <strong><?=$tmp[$data['flag']]?></strong></p>
                            <p>Ghi chú:</p>
                            <p><strong><?=$data['note']?></strong></p>
                                <?php
                                if (!empty($error)) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }
                            ?>
                            <button type="submit" class="btn btn-success px-5 mt-3 mr-2">Chuyển tiền</button>
                            <a href="transfer.php" class="btn btn-danger px-5 mt-3">Hủy</a>
                        </form>                        
                    </div>
                    
                    <?php
                }
            }
        ?>

        <?php
            if(isset($result)){
                if($result['code'] == 0) {
            ?>
            <div class="col-md-6 col-lg-7 col-sm-12">
                <h3 class="text-center text-secondary mt-2 mb-3">Kiểm tra email của bạn</h3>
                <form novalidate method="post" action="execute.php" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                    <div class="form-group">
                        <label for="pass">Confirm OTP</label>
                        <input name="OTP" required class="form-control" type="text" placeholder="Password" id="pass">
                        <input type="hidden" name="amount" value="<?=$amount?>">
                        <input type="hidden" name="name" value="<?=$name?>">
                        <input type="hidden" name="flag" value="<?=$flag?>">
                        <input type="hidden" name="note" value="<?=$note?>">
                        <input type="hidden" name="receiveEmail" value="<?=$receiveEmail?>">
                        <input type="hidden" name="phone" value="<?=$phone?>">
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            if (!empty($confirmMessage)) {
                                echo "<div class='alert alert-success'>$confirmMessage</div>";
                            }
                        ?>
                        <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success px-5">Confirm</button>
                        </div>
                    </div>
                </form> 
            </div>
            <?php
                }
            }
            ?>

        </div>
    </div>
    </body>
    </html>
    