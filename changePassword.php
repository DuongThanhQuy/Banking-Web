<?php
    session_start();
    require_once('api/vendor/autoload.php');
    $error = '';
    if(isset($_POST['password']) && isset($_POST['confirm-password'])) {
        $password = $_POST['password'];
        $confirmPass = $_POST['confirm-password'];
        if (empty($password)) {
            $error = 'Please enter your password';
        } else if (strlen($password) < 6) {
            $error = 'Password must have at least 6 characters';
        }
        else if (empty($confirmPass)) {
            $error = 'Please confirm your password';
        } else if ($password != $confirmPass) {
            $error = 'Password does not match';
        } else {
            $account = new Account();
            $userId = $_SESSION['UserId'];
            $changePassword = $account->changePasswordFirstLogin($userId,$password);
            print_r($changePassword);
            if($changePassword['code'] == 0) {
                $_SESSION['User'] = $account->getUserInfo($userId)['data'];
                $_SESSION['status'] = 0;
                header("Location: index.php");
            } else {
                $error = $changePassword['message'];
            }
        }
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
        <div class="row justify-content-end">
            <button class="btn"><a href="logout.php">Logout</a></button>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h3 class="text-center text-secondary mt-5 mb-3">Change Password</h3>
                    <form novalidate method="post" action="changePassword.php" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                        <div class="form-group">
                            <label for="pass">Password</label>
                                <input name="password" required class="form-control" type="password" placeholder="Password" id="pass">
                        </div>
                        <div class="form-group">
                            <label for="pass2">Confirm Password</label>
                            <input name="confirm-password" required class="form-control" type="password" placeholder="Confirm Password" id="pass2">
                        </div>
                        <div class="form-group">
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        ?>
                            <button type="submit" class="btn btn-success px-5">Change password</button>
                        </div>
                    </form> 
            </div>
        </div>
    </div>
    </body>
    </html>
    