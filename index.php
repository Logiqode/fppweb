<script>
function confirmLogout() {
    var confirmLogout = confirm("Are you sure you want to log out?");
    if (confirmLogout) {
        window.location.href = "logout.php";
    }
}
function confirmDelete(itemId) {
    var confirmDelete = confirm("Are you sure you want to delete this item?");
    if (confirmDelete) {
        window.location.href = "delete_item.php?item_id=" + itemId;
    }
}
function editItem(itemId) {
    window.location.href = "edit_item.php?item_id=" + itemId;
}
function confirmResetInventory() {
    var confirmReset = confirm("Are you sure you want to reset your inventory? This action requires reauthentication.");

    if (confirmReset) {
        window.location.href = "reset_inventory.php";
    }
}
</script>
<?php
session_start();

include("sqlcon.php");

$conn = dbconn();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
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
    <script>
function copyToClipboard(text) {
    var input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);

    input.select();
    document.execCommand('copy');

    document.body.removeChild(input);

    alert('Email copied to clipboard.');
}
</script>
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand text-white" href="#">InventoryManager</a>
          <button class="navbar-toggler" style="color: #ffffff;"" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
      </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

              <li class="nav-item">
                <a class="nav-link text-white" href="inventory.php">Manage</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#aboutus">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#contact">Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="#inifaq">FAQ</a>
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
                      <li><a class="dropdown-item" href="#" onclick="confirmLogout()">Log Out</a></li>

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

<div class="hero" id="hero">
    <div class="container-fluid p-5">
        <div class="row d-flex">
            <div class="col-md-6">
                <p id="heroTitle"><b>Inventory Manager</b></p>
                <p id="heroDesc">Manage your inventory with InventoryManager</p>
                <button class="btn btn-primary herobtn" type="button"><a class="text-white" href="inventory.php">
                Get Started
                </a>
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

<section class="p-5" id="aboutus">
     <div class="container">
        <h2>Get Started with InventoryManager</h2>
        <div class="aboutDesc">
        <p>
            Welcome to InventoryManager, your go-to solution for managing your inventory efficiently.<br>
            Click <a href="#">here</a> or Manage to get started!
        </p>
        </div>
    </div>
</section>
    
<section class="p-5" id="contact">
     <div class="container">
        <h2>Contact</h2>
        <div class="contact">
        <p>
            Have questions or need assistance? Feel free to reach out to us.<br>
            Email us at <a class="emailContact" onclick="copyToClipboard('inventorymanager@gmail.com')">inventorymanager@gmail.com</a> for business inquiries and other help.<br><br>

            Or you could find out more about us at the <a class="emailContact" href="#pageFooter">footer</a> of this page!.
        </p>
        </div>
    </div>
</section>

<section class="p-5" id="inifaq">
        <div class="container">
            <h2>FAQ</h2>
            <h4>Q: What is InventoryManager?</h4>
            <div>
                <p class="aboutDesc">A: The perfect tool for managing your business's inventory.</p>
            </div>
            <h4>Q: Is InventoryManager free?</h4>
                <p class="aboutDesc">A: Our application is free for personal or commercial use!</p>
            <h4>Q: How do I get started in using InventoryManager?</h4>
                <p class="aboutDesc">A: You can watch our short <a href="#hero">video</a> to get started right now!</p>
                <br>
                <p class="aboutDesc">Email us at <a class="emailContact2" onclick="copyToClipboard('inventorymanager@gmail.com')">inventorymanager@gmail.com</a> for business inquiries and other help.</p>
        </div>
    </section>
     
</body>

<footer>
    <div class="text-white text-center" id="pageFooter">
        <div class="p-2 text-center social">
            <a href="#"><i class="m-3 fa-brands fa-instagram fa-xl" style="color: white;"></i></a>
            <a href="#"><i class="m-3 fa fa-whatsapp fa-xl" style="color: white;"></i></a>
            <a href="#"><i class="m-3 fa-brands fa-facebook fa-xl" style="color: white;"></i></a>
            <a href="https://github.com/Logiqode/fppweb" target="blank"><i class="m-3 fa-brands fa-github fa-xl" style="color: white;"></i></a>
        </div>
        5025221139 - Jeremy James & Allen Keyo Handika - 5025221298<br>
        
        Kuliah Pemrograman Web Jurusan Teknik Informatika ITS (2023). Dosen: Imam Kuswardayan, S.Kom, M.T.
    </div>
</footer>
</html>