<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome to Smart Laundry</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</p>
    <p><a href="logout.php">Logout</a></p>
    <p><a href="index.php">Go to Home Page</a></p>
</body>
</html>