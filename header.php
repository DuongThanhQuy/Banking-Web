 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center justify-content-between">


      <a href="index.php" class="logo"><img src="assets/img/logo1.png" alt="" class="img-fluid">TECH BANK</a>
     

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="index.php">Home</a></li>
          <li><a class="nav-link scrollto " href="#info">Guide Banking</a></li>
          <li><a class="nav-link scrollto" href="#services">Services</a></li>
        
          <li><a class="nav-link scrollto" href="#testimonials">About</a></li>
          
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
          <?php
            if(isset($_SESSION['UserId'])) {
              echo "<li><a class='getstarted scrollto' href='user-info.php'>My Account</a></li>";
              echo "<li><a class='getstarted scrollto' href='logout.php'>Logout</a></li>";
            }
            else {
              echo "<li><a class='getstarted scrollto' href='login.php'>Login</a></li>";
            }
          ?>
          
        </ul>
        <!-- <i class="bi bi-list mobile-nav-toggle"></i> -->
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">

    <div class="container-fluid" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <h1>End-of-term project in Web and Application Programming.</h1>
          <?php
            if(isset($_SESSION['UserId'])) {
              $UserName = $_SESSION['User']['FullName'];
              echo "<div class='heroBtn'><a class='btn-get-started scrollto' href='index.php'>Hello, " .$UserName . "</a></div>";
            }
            else {
              ?>
          <div class="heroBtn">
            <div><a href="" class="btn-get-started scrollto">LOGIN</a></div>  
          </div>
              <?php } ?>
        </div>
        <div class="col-xl-4 col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="150">
          <img src="assets/img/bank1.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->