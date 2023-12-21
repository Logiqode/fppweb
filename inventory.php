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
function confirmDeleteAll() {
    var confirmDeleteAll = confirm("Are you sure you want to delete all items?");
    if (confirmDeleteAll) {
        // Redirect to delete all items PHP script
        window.location.href = "reset.php";
    }
}
</script>
<?php
include("sqlcon.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Add item to inventory
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addItem'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $item_desc = $_POST['item_desc']; // New line for description

    $conn = dbconn();
    $user_id = $_SESSION['user_id'];

    // Retrieve the last used item_id
    $lastItemIdResult = $conn->query("SELECT MAX(item_id) as max_id FROM inventory");
    $lastItemIdRow = $lastItemIdResult->fetch_assoc();
    $lastItemId = $lastItemIdRow['max_id'];

    // Calculate the next available item_id
    $nextItemId = ($lastItemId === null) ? 1 : $lastItemId + 1;

    $stmt = $conn->prepare("INSERT INTO inventory (item_id, user_id, item_name, quantity, item_desc) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $nextItemId, $user_id, $item_name, $quantity, $item_desc);

    if ($stmt->execute()) {
        // Item added successfully, redirect to inventory page
        header("Location: inventory.php");
        exit();
    } else {
        $addStatus = "Failed to add item.";
    }

    $stmt->close();
    $conn->close();
}

// Check if the item ID is provided for deletion
if (isset($_POST['deleteItem'])) {
    $itemId = $_POST['item_id'];

    $conn = dbconn();

    // Prepare and execute the DELETE query
    $stmt = $conn->prepare("DELETE FROM inventory WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        // After successful deletion, find the minimum available item ID
        $result = $conn->query("SELECT MIN(item_id) as min_id FROM inventory");
        $row = $result->fetch_assoc();
        $minItemId = $row['min_id'];

        // If there is no minimum available item ID, set it to 1
        $nextItemId = ($minItemId === null) ? 1 : $minItemId;

        // Redirect to inventory page
        header("Location: inventory.php");
        exit();
    } else {
        // Failed to delete item
        echo "Failed to delete item.";
    }

    $stmt->close();
    $conn->close();
}

// Retrieve the user's inventory
$conn = dbconn();
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM inventory WHERE user_id = $user_id");
$inventoryItems = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
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
    // Create a temporary input element
    var input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);

    // Select and copy the text
    input.select();
    document.execCommand('copy');

    // Remove the temporary input element
    document.body.removeChild(input);

    // Alert the user (you can remove this if not needed)
    alert('Copied to clipboard.');
}
</script>
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

    <div class="heroInventory" id="hero">
        <div class="container-fluid p-5">
            <div class="inventoryBox">

            <!-- welcome -->
            <h1 class=pb-3>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

            <!-- Add item form -->
            <h3>Add Items</h3>
            <form method="post" action="inventory.php">
                <div class="mb-3">
                    <label for="item_name" class="form-label">Item Name:</label>
                    <input type="text" name="item_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="item_desc" class="form-label">Item Description:</label>
                    <textarea name="item_desc" class="form-control"></textarea>
                </div>

                <button type="submit" name="addItem" class="btn btn-primary">Add Item</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteAll()"> Delete All </button>
            </form>

            <div class="container m-5">
                <?php if (isset($addStatus)): ?>
                    <p><?php echo $addStatus; ?></p>
                <?php endif; ?>
            </div>

            <h3>Your Inventory</h3>

            <!-- Table to display inventory items -->
            <table class="table">
            <thead>
                <tr>
                    <th scope="col">Item ID</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventoryItems as $item): ?>
                <tr>
                    <td><?php echo $item['item_id']; ?></td>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['item_desc']; ?></td>
                    <td>
                    <button type="button" class="btn btn-primary" onclick="editItem(<?php echo $item['item_id']; ?>)">
                            <i class="fas fa-edit"></i> Edit
                    </button>
                    <!-- Delete button with FontAwesome icon -->
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $item['item_id']; ?>)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

            </div>
        </div>
    </div>

    
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
