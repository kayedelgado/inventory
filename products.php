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
    $category_id = (int) ($_POST["category_id"] ?? 0);
    $quantity = (int) ($_POST["quantity"] ?? 0);
    $price = (float) ($_POST["price"] ?? 0);

    if ($name === "" || $category_id <= 0) {
        $error = "Product name and category are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO products (name, category_id, quantity, price) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("siid", $name, $category_id, $quantity, $price);

        if ($stmt->execute()) {
            $success = "Product added successfully.";
        } else {
            $error = "Could not add product.";
        }

        $stmt->close();
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$products = $conn->query(
    "SELECT p.id, p.name, p.quantity, p.price, c.name AS category_name
     FROM products p
     INNER JOIN categories c ON p.category_id = c.id
     ORDER BY p.id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card wide-card">
            <div class="top-bar">
                <h2>Products</h2>
                <a class="btn logout-btn" href="logout.php">Logout</a>
            </div>

            <div class="actions">
                <a class="btn secondary-btn" href="dashboard.php">Dashboard</a>
                <a class="btn secondary-btn" href="categories.php">Categories</a>
            </div>

            <?php if ($error !== ""): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($categories && $categories->num_rows > 0): ?>
                <form method="POST" action="products.php">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select category</option>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo (int) $category["id"]; ?>">
                                    <?php echo htmlspecialchars($category["name"]); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0" value="0" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" value="0.00" required>
                    </div>

                    <button type="submit">Add Product</button>
                </form>
            <?php else: ?>
                <div class="error">
                    You must add at least one category before creating products.
                </div>
            <?php endif; ?>

            <h3>Product List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products && $products->num_rows > 0): ?>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo (int) $row["id"]; ?></td>
                                <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                <td><?php echo htmlspecialchars($row["category_name"]); ?></td>
                                <td><?php echo (int) $row["quantity"]; ?></td>
                                <td><?php echo number_format((float) $row["price"], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No products yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
