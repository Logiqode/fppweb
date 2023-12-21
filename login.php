<?php
include("sqlcon.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = dbconn();

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit();
    } else {
        echo "Login failed.";
    }

    $stmt->close();
    $conn->close();
}
?>


<form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">Login</button>
</form>
