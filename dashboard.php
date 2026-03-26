<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card dashboard-wrap">
            <div class="top-bar">
                <h2>Inventory Dashboard</h2>
                <a class="btn logout-btn" href="logout.php">Logout</a>
            </div>
            <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>.</p>
            <p>You are now logged in to your simple inventory system.</p>

            <div class="actions">
                <a class="btn secondary-btn" href="categories.php">Manage Categories</a>
                <a class="btn secondary-btn" href="products.php">Manage Products</a>
            </div>
        </div>
    </div>
</body>
</html>
