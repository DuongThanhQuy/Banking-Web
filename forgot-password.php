<?php
    require_once('api/vendor/autoload.php');
    $error = "";
    $email = "";
    if(isset($_POST['email'])) {
        $email = $_POST['email'];
        if(strlen($email) == 0) {
            $error = "Please enter your email";
        }
        $account = new Account();
        $result = $account->forgotPassword($email);
        if($result['code'] == 1) {
            $error = $result['message'];
        }
    }
?>
<DOCTYPE html>
    <html lang="en">
    <head>
        <title>Forgot Password</title>
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
            <div class="col-md-6 col-lg-5">
                <h3 class="text-center text-secondary mt-5 mb-3">Enter your email</h3>
                    <form novalidate method="post" action="forgot-password.php" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                        <div class="form-group mb-3">
                            <label for="user">Email</label>
                            <input name="email" type="email" id="email"  class="form-control">
                        </div>
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        ?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success px-5">Confirm</button>
                        </div>
                    </form>
                    <?php 
                        if(isset($result)) {
                            if($result['code'] == 0) {
                        ?>
                        <p><?=$result['message']?></p>
                        <form novalidate method="post" action="confirm-otp.php" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                            <div class="form-group">
                                <label for="pass">Confirm OTP</label>
                                <input name="OTP" required class="form-control" type="text" placeholder="Password" id="pass">
                                <input name="email" required class="form-control" type="hidden"  value="<?=$email?>">
                                <?php
                                    if (!empty($confirmError)) {
                                        echo "<div class='alert alert-danger'>$confirmError</div>";
                                    }
                                ?>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-success px-5">Confirm</button>
                                </div>
                            </div>
                        </form> 
                        <?php
                            }
                        }
                    ?>
            </div>
        </div>
    </div>
    </body>
    </html>
    