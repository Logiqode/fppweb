<?php
include("sqlcon.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = dbconn();
$user_id = $_SESSION['user_id'];

// Delete all items for the user
$sql = "DELETE FROM inventory WHERE user_id = $user_id";
if ($conn->query($sql)) {
    // Redirect to inventory page after successful deletion
    header("Location: inventory.php");
    exit();
} else {
    echo "Failed to delete all items.";
}

$conn->close();
?>