<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$balance = $user['balance'];

// Fetch transaction history (assuming there's a transactions table)
$transactions = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$transactions->execute([$user_id]);
$transactions_list = $transactions->fetchAll();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Balance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .welcome {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .recharge-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .recharge-button:hover {
            background-color: #45a049;
        }
        .logout {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .logout:hover {
            background-color: #e53935;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
    </style>
    <script>
        function validateForm() {
            var rechargeAmount = document.getElementById('rechargeAmount').value;
            if (isNaN(rechargeAmount) || rechargeAmount <= 0) {
                alert('Please enter a valid recharge amount.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="welcome">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <h4>Your user id is <?php echo htmlspecialchars($user_id); ?>.</h4>
    </div>
    <div class="content">
        <p>Your current balance is: $<?php echo htmlspecialchars($balance); ?></p>
        <h3>Transaction History</h3>
        <ul>
            <?php foreach ($transactions_list as $transaction): ?>
                <li><?php echo htmlspecialchars($transaction['transaction_date']); ?> - $<?php echo htmlspecialchars($transaction['amount']); ?> - <?php echo htmlspecialchars($transaction['transaction_type']); ?></li>
            <?php endforeach; ?>
        </ul>
        <form action="recharge.php" method="post" onsubmit="return validateForm()">
            <label for="rechargeAmount">Recharge Amount: $</label>
            <input type="text" id="rechargeAmount" name="rechargeAmount">
            <button type="submit" class="recharge-button">Recharge</button>
        </form>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
