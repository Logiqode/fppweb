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
        // Redirect to delete item PHP script with the item ID
        window.location.href = "delete_item.php?item_id=" + itemId;
    }
}
function editItem(itemId) {
    // Redirect to the edit item PHP script with the item ID
    window.location.href = "edit_item.php?item_id=" + itemId;
}
function confirmResetInventory() {
    var confirmReset = confirm("Are you sure you want to reset your inventory? This action requires reauthentication.");

    if (confirmReset) {
        // Redirect to the reset inventory PHP script
        window.location.href = "reset_inventory.php";
    }
}
</script>
<?php
include("sqlcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if email is already taken
    $conn = dbconn();
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $registrationStatus = "Email has already been taken.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $registrationStatus = "Username has already been taken.";
        } else {
            if (strlen($password) < 8) {
                $registrationStatus = "Password should be 8 characters or longer.";
            } else {
                $result = $conn->query("SELECT MAX(id) FROM users");
                $row = $result->fetch_assoc();
                $maxID = $row['MAX(id)'];

                $newID = $maxID + 1;

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (id, email, username, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $newID, $email, $username, $hashedPassword);

                if ($stmt->execute()) {
                    $registrationStatus = "Registration successful!";
                } else {
                    $registrationStatus = "Registration failed.";
                }
            }
        }
    }

    $stmt->close();
    $conn->close();
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
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand text-white" href="index.php">InventoryManager</a>
          <button class="navbar-toggler" style="color: #ffffff;"" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
      </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

              <li class="nav-item">
                <a class="nav-link text-white" href="inventory.php">Manage</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="index.php#aboutus">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="index.php#contact">Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white" href="index.php#inifaq">FAQ</a>
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

    <div class="hero">
        <div class="login-container">
            <div class="login-box text-align-center">
                <h2 class="mb-3"> Create an account </h2>
                <form method="post" action="">
                    <div class="login-info">
                        <label for="email">Email:</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="login-info">
                        <label for="username">Username:</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="login-info">
                        <label for="password">Password:</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" name="register">Register</button>
                    <?php
                    if (isset($registrationStatus) && $registrationStatus === "Registration successful!") {
                    echo "<p>$registrationStatus Please <a href='login.php'>log in</a> again.</p>";
                    } else if (isset($registrationStatus)){
                        echo "<p>$registrationStatus</p>";
                    }
                    ?>
                </form>
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
