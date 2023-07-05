<?php
    session_start();
    if (isset($_SESSION['UserId'])) {
        header('Location: index.php');
        exit();
    }
    $error = "";
    require_once('api/vendor/autoload.php');
    if(isset($_POST['name']) && isset($_POST['birthday']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['front-IDCard']) && isset($_POST['back-IDCard'])) {
        $name = $_POST['name'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $front_Id = $_POST['front-IDCard'];
        $back_Id = $_POST['back-IDCard'];

        $account = new Account();
        $result = $account->register($name, $birthday, $address, $email, $phone, $front_Id, $back_Id);
        if($result['code'] == 0) {
            echo $result['message'];
            header("Location: login.php");
        } else {   
            $error = $result['message'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Register TECH Bank</title>
    <link href="../assets/img/favicon-32x32.png" rel="icon">
    <link href="../assets/img/apple-icon-180x180.png" rel="apple-touch-icon">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="style.css" rel="stylesheet">
    <style>
        .card-body {
            padding: 85px 60px;
            background: url('./assets/img/login2.jpg');
            background-size: 100%;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border my-5 p-4 rounded mx-3 card-body">
                <div class="brand-wrapper">
                    <img class="userloginimg" src="./assets/img/logo3.png" >
                    <p>TECH BANK</p>
                </div>
                <h3 class="text-center mt-2 mb-3 mb-3 login">Create a new account</h3>
                <form method="post" action="register.php">
                    <div class="form-group mb-3">
                        <label for="lastname" class="mr-2">Họ và tên:</label>
                        <input  name="name" type="text" id="name" class="form-control" placeholder="họ và tên" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="user">Ngày sinh</label>
                        <input name="birthday" type="date" id="birthday" class="form-control" placeholder="ngày sinh" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="user">Địa chỉ</label>
                        <input name="address" type="text" id="address"  class="form-control" placeholder="địa chỉ" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="user">Email</label>
                        <input name="email" type="email" id="email"  class="form-control" placeholder="email" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="user">Số điện thoại</label>
                        <input name="phone" type="text" id="phone"  class="form-control" placeholder="số điện thoại" required>
                    </div>

                    <div class="form-group">
                        <div class="custom-file">
                            <input name="front-IDCard" type="file" class="custom-file-input" id="front" accept="image/gif, image/jpeg, image/png, image/bmp">
                            <label class="custom-file-label" for="customFile">Ảnh mặt trước CMND</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-file">
                            <input name="back-IDCard" type="file" class="custom-file-input" id="back" accept="image/gif, image/jpeg, image/png, image/bmp">
                            <label class="custom-file-label" for="customFile">Ảnh mặt sau CMND</label>
                        </div>
                    </div>
                    <?php
                        if (!empty($error)) {
                            echo "<p class='text-danger'>$error</p>";
                        }
                        ?>
                    <div>
                        <button type="submit" class="btn btn-success px-5 mt-3 mr-2">Register</button>
                        <button type="reset" class="btn btn-outline-success px-5 mt-3">Reset</button>
                    </div>
    
                    <div class="form-fun">
                        <p class="mt-2" style="color: white">Already have an account? <a href="login.php">Login</a> now.</p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>