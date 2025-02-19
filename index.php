<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label>Email: </label>
        <input type="email" name="email" required><br>
        <label>Password: </label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </form>
</body>
</html>
