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
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateItem'])) {
    $itemId = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $item_desc = $_POST['item_desc'];

    $conn = dbconn();

    // Update the item in the database
    $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, quantity = ?, item_desc = ? WHERE item_id = ?");
    $stmt->bind_param("sssi", $item_name, $quantity, $item_desc, $itemId);

    if ($stmt->execute()) {
        // Item updated successfully, redirect to inventory page
        header("Location: inventory.php");
        exit();
    } else {
        echo "Failed to update item.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to inventory page if the form is not submitted
    header("Location: inventory.php");
    exit();
}
?>
