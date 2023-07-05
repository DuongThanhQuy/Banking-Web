<?php
    session_start();
    if (isset($_SESSION['UserId'])) {
        header('Location: index.php');
        exit();
    }
    require_once('api/vendor/autoload.php');
    $error = '';
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (empty($username)) {
            $error = 'Please enter your username';
        }
        else if (empty($password)) {
            $error = 'Please enter your password';
        }
        else {
            $account = new Account();
            $acc = $account->login($username,$password);
            if($acc['code'] >= -1) {
                $_SESSION['UserId'] = $acc['data']['UserID'];
                $_SESSION['status'] = $acc['data']['status'];
                if($acc['code'] == 4) {
                    header("Location: admin/index.php");
                }
                if ($acc['code'] >= 0){
                    $userId = $_SESSION['UserId'];
                    $user = new User();
                    $_SESSION['User'] = $user->getUserInfo($userId)['data'];
                    header("Location: index.php");
                } else {
                    header("Location: changePassword.php");
                }
            } else if ($acc['code'] == -2) {
                $error = $acc['message'];
            }  
            // else {
                
            // }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login TECH Bank</title>
    <!-- Favicons -->
    <link href="../assets/img/favicon-32x32.png" rel="icon">
    <link href="../assets/img/apple-icon-180x180.png" rel="apple-touch-icon">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0" >
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img class="userloginimg" src="./assets/img/logo3.png" >
                                <p>TECH BANK</p>
                            </div>
                            <!-- Login Form -->
                            <form class="form" method="post" action="login.php">
                                <label class="login">WELCOME</label>
                                <div class="form-group">
                                    <label for="username" class="sr-only"></label>Username
                                    <input type="text" name="username" id="Username" class="form-control" placeholder="username" required>
                                    <p id="alert1" style="color: rgb(255, 106, 0);"></p>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only"></label>Password
                                    <input type="password" name="password" id="password" class="form-control" placeholder="***********" required>
                                </div>
                                <?php
                                if (!empty($error)) {
                                    echo "<p class='text-white'>$error</p>";
                                }
                                ?>
                                <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                            </form>
                            <div class="form_fun">
                                <a href="forgot-password.php" class="help"><label class="label_help" >Forgot password?</a>
                                <a href="register.php" class="help ml-2"><label class="label_help" >Register here</a>
                            </div>
                            <a href="index.php" class="help"><label class="label_help" >Home</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <img src="./assets/img/b3.jpg" alt="login" class="login-card-img" style="height: 530px">
                    </div>
                </div>
            </div>
        </div>
    </main> 
    
</body>

</html>