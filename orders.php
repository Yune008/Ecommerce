<?php
$conn = new mysqli("localhost", "root", "", "Landersdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Remove order if requested
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: Orders.php");
    exit();
}

// Checkout (clear all orders)
if (isset($_POST['checkout'])) {
    $conn->query("DELETE FROM orders");
    header("Location: Orders.php");
    exit();
}

$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title> Order Info </title>
<link rel="stylesheet" href="Style.css">
<style>
.table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#333; color:white; }
.button { padding:6px 12px; background:#aad190; border:none; border-radius:5px; cursor:pointer; }
.button:hover { background:#8cb474; }
a.button { text-decoration:none; color:black; display:inline-block; }
.checkout-btn { margin-top:15px; padding:10px 20px; font-size:16px; }
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
<a href="#" id="explore">
    <img src="img/Explore.jpg" alt="Explore">
</a>
<h2 style="text-align:center;"> Orders </h2> &nbsp&nbsp
<a href="Home.php" class="button"> Go Back </a>
<br><br>

<?php if ($result->num_rows > 0): ?>
<table class="table">
<tr>
    <th> ID </th>
    <th> Product </th>
    <th> Quantity </th>
    <th> Total </th>
    <th> Customer </th>
    <th> Contact No.</th>
    <th> Action </th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= $row['quantity'] ?></td>
    <td>₱<?= number_format($row['total'],2) ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['contact_num']) ?></td>
    <td>
        <a href="Orders.php?remove=<?= $row['id'] ?>" class="button">Remove</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<form method="POST" style="text-align:center;">
    <button type="submit" name="checkout" class="button checkout-btn">Checkout / Clear All Orders</button>
</form>

<?php else: ?>
<p style="text-align:center;"> No orders yet </p>
<?php endif; ?>
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
