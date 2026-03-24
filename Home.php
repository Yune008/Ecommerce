<?php
session_start();

$conn = new mysqli("localhost", "root", "", "Landersdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    $stmt = $conn->prepare(
        "SELECT product_id, product_name, product_price, product_stock 
         FROM products WHERE product_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if (isset($_SESSION['cart'][$id])) {
            if ($_SESSION['cart'][$id]['quantity'] < $row['product_stock']) {
                $_SESSION['cart'][$id]['quantity']++;
            }
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $row['product_name'],
                'price' => $row['product_price'],
                'stock' => $row['product_stock'],
                'quantity' => 1
            ];
        }
    }
    header("Location: Home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LANDERS</title>
<link rel="stylesheet" href="Style.css">
</head>
<body>

<header id="header">
    <div id="logo-container">
        <img src="img/Logo.png" alt="Logo">
        <span id="name">Landers</span>
    </div>
    <nav id="nav">
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="About.html">About</a></li>
            <li><a href="Orders.php">Orders</a></li>
            <li><a href="Cart.php">Cart</a></li>
            <li><a href="Admin.php" class="admin-btn">Admin</a></li>
        </ul>
    </nav>
</header>
<a href="#" id="explore">
    <img src="img/Explore.jpg" alt="Explore">
</a>

<h2 style="text-align: center;">Suggested Products</h2>
<div class="product">
<?php
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()):
    $img = $row['product_image']
        ? 'data:image/jpeg;base64,' . base64_encode($row['product_image'])
        : 'img/no-image.png';
?>
<div class="card">
    <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
    <h5><?= htmlspecialchars($row['product_name']) ?></h5>
    <p class="price">₱<?= number_format($row['product_price'], 2) ?></p>
    <p class="stock">Stock: <?= $row['product_stock'] ?></p>

    <div class="card-buttons">
        <a href="Home.php?add=<?= $row['product_id'] ?>" class="card-button">Add to Cart</a>
        <form action="place_order.php" method="POST" class="buy-form">
            <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['product_name']) ?>">
            <input type="hidden" name="price" value="<?= $row['product_price'] ?>">
            <input type="number" name="quantity" min="1" max="<?= $row['product_stock'] ?>" value="1" required>
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="text" name="contact_num" placeholder="Contact Number" required>
            <button type="submit" class="card-button buy">Buy Now</button>
        </form>
    </div>
</div>
<?php endwhile; ?>
</div>
<footer>
<div class="footer">
    <img src="img/Logo.png" alt="Logo" id="footerlogo">
        <h5> Download the App </h5>
        <p> Available via App Store and Playstore </p>
    <table style="margin: 0 auto; border-collapse: collapse; text-align: center;">
        <tr>
            <td rowspan="2"><img src="img/qrcode.png" alt="QR Code" style="width: 80px; height: 80px;"></td>
            <td><img src="img/appstore.png" alt="App Store" style="width: 100px; margin-bottom: 5px;"></td>
        </tr>
        <tr>
            <td><img src="img/googleplay.png" alt="Google Play" style="width: 100px;"></td>
        </tr>
    </table>
</div>
</footer>
</body>
</html>
