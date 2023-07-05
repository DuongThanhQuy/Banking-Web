<?php
    session_start();
    require_once('../api/vendor/autoload.php');
    if(isset($_SESSION['status'])) {
        if($_SESSION['status'] != 4) {
          header("Location: ../index.php");
        }
    } else {
        header("Location: ../index.php");
    }
    $user = new User();
    $account = new Account();
    $allUser = $user->getAllUser();
    $allAccount = $account->getAllAccount();
    $totalUser = sizeOf($allUser);
    $accountActive = $account->getAccountActive();
    $accountToActive = $account->getAccountToActive();
    $accountDisable = $account->getAccountDisable();
    $accountInvlAccess = $account->getAccountDisableForIvl();
    $accountStatus = array(-2 => 'Vô hiệu hóa',-1 => 'Mới đăng kí',0 => 'Chờ xác minh', 1 => 'Đã xác minh', 2 => 'Yêu cầu bổ sung thông tin', 3 => 'Chờ cập nhật');
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard TECH Bank</title>

    <!-- Favicons -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/vendor/boxicons/css/boxicons.css">
    <link rel="stylesheet" href="../assets/vendor/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/vendor/boxicons/css/animations.css">
    <link rel="stylesheet" href="../assets/vendor/boxicons/css/transformations.css">


    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!--fontawesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.2.0/dist/chart.min.js"></script>

</head>

<body class="dark_bg">

    <div id="wrapper">
        <div class="overlay"></div>

        <!-- Sidebar -->
        <nav class="fixed-top align-top" id="sidebar-wrapper" role="navigation">
            <div class="simplebar-content" style="padding: 0px;">
                <a class="sidebar-brand" href="../index.php">
                    <span class="align-middle">TECH BANK</span>
                </a>

                <ul class="navbar-nav align-self-stretch">

                    <!-- <li class="sidebar-header">
                        Pages
                    </li> -->
                    <li class="">

                        <a class="nav-link text-left active" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="flaticon-bar-chart-1"></i><i class="bx bxs-dashboard ico"></i> Dashboard
                        </a>
                    </li>

                    <li class="menuHover">
                        <a href="" class="nav-link text-left" role="button">
                            <i class="flaticon-bar-chart-1"></i><i class="bx bx-transfer ico"></i> Transfer
                        </a>
                    </li>

                    <li class="menuHover box-icon">
                        <a href="verify-transaction.php" class="nav-link text-left" role="button">
                            <i class="flaticon-bar-chart-1"></i> <i class="bx bx-check-circle ico"></i> Verify transaction
                        </a>
                    </li>



            
                    <li class="menuHover">
                        <a class="nav-link text-left" role="button" href="../logout.php">
                            <i class="flaticon-map"></i><i class="bx bx-log-out ico"></i> Logout
                        </a>
                    </li>

                </ul>


            </div>


        </nav>
        <!-- /#sidebar-wrapper -->


        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div id="content">
                <div class="container-fluid p-0 px-lg-0 px-md-0">
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light gray_bg my-navbar">

                        <!-- Sidebar Toggle (Topbar) -->
                        <div type="button" id="bar" class="nav-icon1 hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
                            <span class="light_bg"></span>
                            <span class="light_bg"></span>
                            <span class="light_bg"></span>
                        </div>


                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown  d-sm-none">

                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                         

                            <!-- Nav Item - User Information -->
                            <li>
                                <a class="nav-link" href="../logout.php" role="button">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">ADMIN</span>
                                </a>
                            </li>

                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid px-lg-4 dark_bg light">
                        <div class="row">
                            <div class="col-md-12 mt-lg-4 mt-4">
                                <!-- Page Heading -->
                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <h1 class="h3 mb-0 light">Dashboard</h1>
                                    <div>
                                        <a href="" role="button" class="btn btn-success btn-circle btn-md ShowHide mr-5">
                                            <div><i class='bx bxs-down-arrow-alt bx-sm'></i></div>Deposit
                                        </a>
                                        <a href="" role="button" class="btn btn-danger btn-circle btn-md ShowHide mr-5">
                                            <div><i class='bx bxs-up-arrow-alt bx-sm'></i></div>Withdraw
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Total Customer</h5>
                                                <h1 class="display-5 mt-1 mb-3 light"></h1>
                                                <div class="mb-1">
                                                    <span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i></span>
                                                    <span class="text-muted light"><?=$totalUser?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4">Active Accounts</h5>
                                                <h1 class="display-5 mt-1 mb-3 light"></h1>
                                                <div class="mb-1">
                                                    <span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i></span>
                                                    <span class="text-muted light"><?=sizeOf($accountActive)?></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4">Accounts for Verification</h5>
                                                <h1 class="display-5 mt-1 mb-3 light"></h1>
                                                <div class="mb-1">
                                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i></span>
                                                    <span class="text-muted light"><?=sizeOf($accountToActive)?></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4">Deactivated Accounts</h5>
                                                <h1 class="display-5 mt-1 mb-3 light"></h1>
                                                <div class="mb-1">
                                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i></span>
                                                    <span class="text-muted light"><?=sizeOf($accountDisable)?></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Tài khoản chờ kích hoạt</h5>
                                                <?php
                                                    if(sizeOf($accountToActive)==0) {

                                                    } else {
                                                ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th class="light">UserID</th>
                                                            <th class="light">UserName</th>
                                                            <th class="light">Status</th>
                                                            <th class="light">Day create</th>
                                                            <th class="light">Actions</th>
                                                        </tr>
                                            <?php
                                                foreach($accountToActive as $acc) {
                                                    $userId = $acc['UserID'];
                                                    $userName = $acc['username'];
                                                    $status = $accountStatus[$acc['status']];
                                                    $dayCreate = $acc['day_create'];
                                                    $info = $user->getUserInfo($userId);
                                                    // die($info['data']['FullName']);
                                            ?>
                                                    <tr class="item">
                                                        <td class="light"><?=$userId?></td>
                                                        <td class="light"><?=$userName?></td>
                                                        <td class="light"><?=$status?></td>
                                                        <td class="light"><?=$dayCreate?></td>
                                                        <td>
                                                            <a role="button" href="" data-toggle="modal" data-target="#edit-modal" class="btn btn-custo" onclick="editDialog('<?=$userId?>','<?=$info['data']['FullName']?>','<?=$info['data']['Phone']?>','<?=$info['data']['Email']?>','<?=$acc['status']?>','<?=$accountStatus[$acc['status']]?>')"><i class='bx bxs-pencil'></i> Edit Account</a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                echo "</table>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Tài khoản đã kích hoạt</h5>
                                                <?php
                                                    if(sizeOf($accountActive)==0) {

                                                    } else {
                                                ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th class="light">UserID</th>
                                                            <th class="light">UserName</th>
                                                            <th class="light">Status</th>
                                                            <th class="light">Day create</th>
                                                            <th class="light">Actions</th>
                                                        </tr>
                                            <?php
                                                foreach($accountActive as $acc) {
                                                    $userId = $acc['UserID'];
                                                    $userName = $acc['username'];
                                                    $status = $accountStatus[$acc['status']];
                                                    $dayCreate = $acc['day_create'];
                                                    $info = $user->getUserInfo($userId);
                                            ?>
                                                    <tr class="item">
                                                        <td class="light"><?=$userId?></td>
                                                        <td class="light"><?=$userName?></td>
                                                        <td class="light"><?=$status?></td>
                                                        <td class="light"><?=$dayCreate?></td>
                                                        <td>
                                                            <a role="button" href="" data-toggle="modal" data-target="#edit-modal" class="btn btn-custo" onclick="editDialog('<?=$userId?>','<?=$info['data']['FullName']?>','<?=$info['data']['Phone']?>','<?=$info['data']['Email']?>','<?=$acc['status']?>','<?=$accountStatus[$acc['status']]?>')"><i class='bx bxs-pencil'></i> Edit Account</a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                echo "</table>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Tài khoản vô hiệu hóa do từ chối xác minh</h5>
                                                <?php
                                                    if(sizeOf($accountDisable)==0) {

                                                    } else {
                                                ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th class="light">UserID</th>
                                                            <th class="light">UserName</th>
                                                            <th class="light">Status</th>
                                                            <th class="light">Day create</th>
                                                            <th class="light">Actions</th>
                                                        </tr>
                                            <?php
                                                foreach($accountDisable as $acc) {
                                                    $userId = $acc['UserID'];
                                                    $userName = $acc['username'];
                                                    $status = $accountStatus[$acc['status']];
                                                    $dayCreate = $acc['day_create'];
                                                    $info = $user->getUserInfo($userId);
                                            ?>
                                                    <tr class="item">
                                                        <td class="light"><?=$userId?></td>
                                                        <td class="light"><?=$userName?></td>
                                                        <td class="light"><?=$status?></td>
                                                        <td class="light"><?=$dayCreate?></td>
                                                        <td>
                                                            <a role="button" href="" data-toggle="modal" data-target="#edit-modal" class="btn btn-custo" onclick="editDialog('<?=$userId?>','<?=$info['data']['FullName']?>','<?=$info['data']['Phone']?>','<?=$info['data']['Email']?>','<?=$acc['status']?>','<?=$accountStatus[$acc['status']]?>')"><i class='bx bxs-pencil'></i> Edit Account</a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                echo "</table>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Tài khoản vô hiệu hóa do đăng nhập sai mật khẩu</h5>
                                                <?php
                                                    if(sizeOf($accountInvlAccess)==0) {

                                                    } else {
                                                ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th class="light">UserID</th>
                                                            <th class="light">UserName</th>
                                                            <th class="light">Status</th>
                                                            <th class="light">Day create</th>
                                                            <th class="light">Actions</th>
                                                        </tr>
                                            <?php
                                                foreach($accountInvlAccess as $acc) {
                                                    $userId = $acc['UserID'];
                                                    $userName = $acc['username'];
                                                    $status = $accountStatus[$acc['status']];
                                                    $dayCreate = $acc['day_create'];
                                                    $info = $user->getUserInfo($userId);
                                            ?>
                                                    <tr class="item">
                                                        <td class="light"><?=$userId?></td>
                                                        <td class="light"><?=$userName?></td>
                                                        <td class="light"><?=$status?></td>
                                                        <td class="light"><?=$dayCreate?></td>
                                                        <td>
                                                            <a role="button" href="" data-toggle="modal" data-target="#edit-modal" class="btn btn-success" onclick="editDialog('<?=$userId?>','<?=$info['data']['FullName']?>','<?=$info['data']['Phone']?>','<?=$info['data']['Email']?>','<?=$acc['status']?>','<?=$accountStatus[$acc['status']]?>')"><i class='bx bxs-pencil'></i> Edit Account</a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                echo "</table>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row justify-content-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="card gray_bg">
                                            <div class="card-body card-shadow">
                                                <h5 class="card-title light mb-4 ">Tất cả tài khoản</h5>
                                                <?php
                                                    if(sizeOf($allAccount)==0) {

                                                    } else {
                                                ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th class="light">UserID</th>
                                                            <th class="light">UserName</th>
                                                            <th class="light">Status</th>
                                                            <th class="light">Day create</th>
                                                            <th class="light">Actions</th>
                                                        </tr>
                                            <?php
                                                foreach($allAccount as $acc) {
                                                    $userId = $acc['UserID'];
                                                    $userName = $acc['username'];
                                                    $status = $accountStatus[$acc['status']];
                                                    $dayCreate = $acc['day_create'];
                                                    $user = new User();
                                                    $info = $user->getUserInfo($userId);
                                            ?>
                                                    <tr class="item">
                                                        <td class="light"><?=$userId?></td>
                                                        <td class="light"><?=$userName?></td>
                                                        <td class="light"><?=$status?></td>
                                                        <td class="light"><?=$dayCreate?></td>
                                                        <td>
                                                            <a role="button" href="" data-toggle="modal" data-target="#edit-modal" class="btn btn-custo" onclick="editDialog('<?=$userId?>','<?=$info['data']['FullName']?>','<?=$info['data']['Phone']?>','<?=$info['data']['Email']?>','<?=$acc['status']?>','<?=$accountStatus[$acc['status']]?>')"><i class='bx bxs-pencil'></i> Edit Account</a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                echo "</table>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <!-- /#page-content-wrapper -->
        </div>
    </div>
    <!-- /#wrapper -->

    
    <div id="edit-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content bg-dark">
                <table id="EditTable" class="table my-auto">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Verify Account</th>
                                <th>Update information</th>
                                <th>Reject Account</th>
                            </tr>
                        </thead>
                        <tbody class="dark_bg">
                            <tr>
                                <th class="light" id="userId"></th>
                                <td class="light" id="userName"></td>
                                <td class="light" id="userPhone"></td>
                                <td class="light" id="userEmail"></td>
                                <td class="light" id="userStatus"></td>
                                <td class="light">
                                    <form action="active-account.php" method="post">
                                        <input type="hidden" name="action" value="active">
                                        <input type="hidden" class="UID" name="userId" value="">
                                        <button type="submit" class="btn btn-success verify_data"><i class='bx bx-user-check'></i> Verify</button>
                                    </form>
                                    
                                </td>
                                <td class="light">
                                    <form action="active-account.php" method="post">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" class="UID" name="userId" value="">
                                        <button id="" class="btn btn-danger reject_data" data-dismiss="modal"><i class='bx bx-user-x'></i> Request Update</button>
                                    </form>
                                </td>
                                <td class="light">
                                    <form action="active-account.php" method="post">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" class="UID" name="userId" value="">
                                        <button id="" class="btn btn-danger reject_data"><i class='bx bx-user-x'></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>






    <footer class="footer gray_bg w-100">
                        <div class="row text-muted">
                            <div class="col-6 text-left">
                                <p class="mb-0">
                                    <a href="" class="text-muted light"><strong>TECH Bank
                                        </strong></a> 
                                </p>
                            </div>
                            <div class="col-6 text-right">
                                <ul class="list-inline">
                                    <li class="footer-item">
                                        <a class="text-muted light" href="#">Support</a>
                                    </li>
                                    <li class="footer-item">
                                        <a class="text-muted light" href="#">Help Center</a>
                                    </li>
                                    <li class="footer-item">
                                        <a class="text-muted light" href="">Privacy</a>
                                    </li>
                                    <li class="footer-item">
                                        <a class="text-muted light" href="">Terms</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
            </footer>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


    <script>
        $('#bar').click(function() {
            $(this).toggleClass('open');
            $('#page-content-wrapper ,#sidebar-wrapper').toggleClass('toggled');

        });

        function editDialog($id, $name, $phone, $email, $status, $nameStatus) {
            $('#userId').text($id);
            $('#userName').text($name);
            $('#userPhone').text($phone);
            $('#userEmail').text($email);
            $('#userStatus').text($nameStatus);
            $(".UID").val($id);
        }

    </script>

</body>

</html>