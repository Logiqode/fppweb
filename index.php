<?php
session_start();

include("sqlcon.php");

$conn = dbconn();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// $sql = "SELECT * FROM ";
// $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>    
    <title>InventoryManager - FP_PWEB</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    
    <script src="https://kit.fontawesome.com/ca15795a80.js" crossorigin="anonymous"></script>
    <script defer src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>
    
    <!-- <script defer src="script.js"></script> -->
</head>

<body>
    <section>
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand text-white" href="#">InventoryManager</a>
          <button class="navbar-toggler" style="color: #ffffff;"" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
      </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Manage
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#" target="blank">Add</a></li>
                      <li><a class="dropdown-item" href="#" target="blank">Edit</a></li>
                      <li><a class="dropdown-item" href="#" target="blank">Delete</a></li>
                    </ul>
              </li>
              
              <li class="nav-item">
                <a class="nav-link text-white" href="#">Pricing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#">Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#">FAQ</a>
              </li>
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['username'])) {
                //logged in
                    echo '
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    '. $_SESSION['username'] . '
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Profile</a></li>
                      <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                    </ul>
                    </li>';
                } else {
                //guest
                    echo '
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Login
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="login.php">Login</a></li>
                      <li><a class="dropdown-item" href="register.php">Register</a></li>
                    </ul>
                    </li>';
                }
                ?>
                </ul>
            </div>
          </div>
        </div>
    </nav>
    </section>

    <div class="hero">
    <div class="container-fluid p-5">
        <div class="row d-flex">
            <div class="col-md-6">
                <h1 id="heroTitle"><b>InventoryManager</b></h1>
                <p id="heroDesc">Manage your inventory with InventoryManager</p>
                <button class="btn btn-primary herobtn" type="button">
                Learn More
                </button>
            </div>
            <div class="col-md-6 py-3">
                <div class="video-placeholder herovideo">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/ZRtdQ81jPUQ?si=eG0Iz1SrxEvp5OPi" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

    
    
    
        
</body>

<footer>
    <div class="text-white text-center" id="pageFooter">
        5025221139 - Jeremy James & Allen Keyo Handika - 5025221298<br>
        
        Kuliah Pemrograman Web Jurusan Teknik Informatika ITS (2023). Dosen: Imam Kuswardayan, S.Kom, M.T.
    </div>
</footer>
</html>