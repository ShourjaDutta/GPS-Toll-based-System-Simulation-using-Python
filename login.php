<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: balance.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
	        body {
	            display: flex;
	            justify-content: center;
	            align-items: center;
	            height: 100vh; /* Ensures the form is centered vertically */
	            margin: 0; /* Removes default margin */
	            font-family: Arial, sans-serif; /* Optional: Choose your preferred font */
	        }
	        .container {
	            text-align: center;
	            width: 300px; /* Adjust as needed */
	            border: 1px solid #ccc; /* Optional: Add a border for clarity */
	            padding: 20px; /* Optional: Add padding for better appearance */
	            background-color: #f9f9f9; /* Optional: Light background color */
	        }
	        form {
	            margin-top: 20px; /* Optional: Add space between header and form */
	        }
    </style>
</head>
<body bgcolor="#96e9ff">
<div class="container">
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
