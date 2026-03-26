<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $error = "Category name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $success = "Category added successfully.";
        } else {
            $error = "Category already exists or could not be added.";
        }

        $stmt->close();
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card wide-card">
            <div class="top-bar">
                <h2>Categories</h2>
                <a class="btn logout-btn" href="logout.php">Logout</a>
            </div>

            <div class="actions">
                <a class="btn secondary-btn" href="dashboard.php">Dashboard</a>
                <a class="btn secondary-btn" href="products.php">Products</a>
            </div>

            <?php if ($error !== ""): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="categories.php">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <button type="submit">Add Category</button>
            </form>

            <h3>Category List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categories && $categories->num_rows > 0): ?>
                        <?php while ($row = $categories->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo (int) $row["id"]; ?></td>
                                <td><?php echo htmlspecialchars($row["name"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No categories yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
