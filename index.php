<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toll System</title>
    <style>
    	body {
            margin: 0;
            font-family: Arial, sans-serif;
		}
		 .header {
			            background-color: #4CAF50;
			            padding: 20px;
			            text-align: center;
			            color: white;
        }
        .container {
            text-align: center;
        }
        .box {
            display: inline-block;
            background-color: #ffffff;
            border: 2px solid #4CAF50;
            padding: 20px;
            margin: 10px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        .box:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Toll System</h1>
     </div>
        <div class="container">
        <div class="box">
            <a href="register.php">Register</a>
        </div>
        <div class="box">
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
