<?php
include("sqlcon.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    $conn = dbconn();

    $stmt = $conn->prepare("DELETE FROM inventory WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        $result = $conn->query("SELECT MIN(item_id) as min_id FROM inventory");
        $row = $result->fetch_assoc();
        $minItemId = $row['min_id'];

        $nextItemId = ($minItemId === null) ? 1 : $minItemId;

        header("Location: inventory.php");
        exit();
    } else {
        echo "Failed to delete.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: inventory.php");
    exit();
}
?>
