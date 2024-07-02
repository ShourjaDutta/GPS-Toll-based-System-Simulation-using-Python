<?php
$host = 'localhost';
$db = 'toll_system';  // Ensure the database 'toll_system' exists
$user = 'root';  // Default user for XAMPP
$pass = '';  // Default password for XAMPP is empty

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
