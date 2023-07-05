<?php
    session_start();
    require_once('api/vendor/autoload.php');

    $error = "";
    if(isset($_POST['old-password']) && isset($_POST['new-password']) && isset($_POST['new-confirm-password'])) {
        $oldPassword = $_POST['old-password'];
        $newPassword = $_POST['new-password'];
        $confirmNewPass = $_POST['new-confirm-password'];
        if (empty($oldPassword) || empty($newPassword)) {
            $error = 'Please enter your password';
        } else if (strlen($newPassword) < 6) {
            $error = 'Password must have at least 6 characters';
        }
        else if (empty($confirmNewPass)) {
            $error = 'Please confirm your password';
        } else if ($newPassword != $confirmNewPass) {
            $error = 'Password does not match';
        } else {
            $account = new Account();
            $userId = $_SESSION['UserId'];
            $result = $account->changePassword($oldPassword,$newPassword,$userId);
            if($result['code'] == 0) {
                echo $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    } else {

    }
?>

<DOCTYPE html>
    <html lang="en">
    <head>
        <title>Reset user password</title>
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
                <h3 class="text-center text-secondary mt-5 mb-3">Reset Password</h3>
                        <form novalidate method="post" action="reset-password.php" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                            <div class="form-group">
                                <label for="pass">Old password</label>
                                <input name="old-password" required class="form-control" type="password" placeholder="Password" id="pass">
                            </div>
                            <div class="form-group">
                                <label for="pass">Password</label>
                                <input name="new-password" required class="form-control" type="password" placeholder="Password" id="pass">
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <label for="pass2">Confirm Password</label>
                                <input name="new-confirm-password" required class="form-control" type="password" placeholder="Confirm Password" id="pass2">
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            ?>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success px-5">Change password</button>
                            </div>
                        </form> 
            </div>
        </div>
    </div>
    </body>
    </html>
    