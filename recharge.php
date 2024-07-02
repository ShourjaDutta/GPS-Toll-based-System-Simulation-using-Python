<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rechargeAmount = $_POST['rechargeAmount'];

    if (is_numeric($rechargeAmount) && $rechargeAmount > 0) {
        // Update the balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$rechargeAmount, $user_id]);

        // Log the transaction (if you have a transactions table)
        $transactionStmt = $pdo->prepare("INSERT INTO transactions (user_id, amount,transaction_type) VALUES (?, ?,?)");
        $transactionStmt->execute([$user_id, $rechargeAmount,"recharge"]);

        // Redirect back to balance page
        header('Location: balance.php');
        exit;
    } else {
        echo "Invalid recharge amount.";
    }
} else {
    echo "Invalid request method.";
}
?>
