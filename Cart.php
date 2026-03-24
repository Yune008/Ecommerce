<?php
session_start();
$conn = new mysqli("localhost", "root", "", "Landersdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add product to cart
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    $sql = "SELECT product_id, product_name, product_price, product_stock FROM products WHERE product_id = $id";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
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
        header("Location: cart.php");
        exit();
    }
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][intval($_GET['remove'])]);
    header("Location: cart.php"); exit();
}
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php"); exit();
}

// Calculate total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Cart </title>
<link rel="stylesheet" href="Style.css">
<style>
.cart-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.cart-table th, .cart-table td { border: 1px solid #ccc; padding: 10px; text-align: center; }
.cart-table th { background-color: #f0f0f0; }
.cart-img { width: 80px; height: 80px; object-fit: cover; }
.cart-actions a { padding: 5px 10px; background-color: #ddd; text-decoration: none; color: black; border-radius: 4px; margin: 0 3px; }
.cart-actions a:hover { background-color: #bbb; }
.back-btn { text-decoration: none; padding: 8px 12px; background-color: #ccc; color: black; border-radius: 5px; }
.back-btn:hover { background-color: #aaa; }
.total { text-align: right; font-size: 1.2em; margin-top: 20px; }
</style>
</head>
<body>
<header id="header">
    <div id="logo-container">
        <a href="Logo.html" id="logo"><img src="img/Logo.png" alt="Logo"></a>
        <span id="name"> Landers </span>
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
<center>
<a href="#" id="explore">
    <img src="img/Explore.jpg" alt="Explore">
</a>
<h2> Your Cart </h2>
<a href="Home.php" class="back-btn"> Continue Shopping</a>

<?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table class="cart-table">
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
        <?php
            $imgResult = $conn->query("SELECT product_image FROM products WHERE product_id = $id");
            $imgRow = $imgResult->fetch_assoc();
            $imgData = $imgRow && $imgRow['product_image'] ? 'data:image/jpeg;base64,' . base64_encode($imgRow['product_image']) : 'img/no-image.png';
        ?>
        <tr>
            <td><img src="<?= $imgData ?>" class="cart-img" alt="<?= htmlspecialchars($item['name']) ?>"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>₱<?= number_format($item['price'],2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>₱<?= number_format($item['price']*$item['quantity'],2) ?></td>
            <td class="cart-actions">
                <a href="cart.php?remove=<?= $id ?>">Remove</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p class="total"><strong>Total: ₱ <?= number_format($total,2) ?></strong></p>
    <a href="cart.php?clear" class="back-btn">Clear Cart</a>
<?php endif; ?>
</center>
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
