<?php
$conn = new mysqli("localhost", "root", "", "Landersdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = file_get_contents($_FILES['image']['tmp_name']);

    $sql = "INSERT INTO products (product_name, product_price, product_stock, product_image)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $name, $price, $stock, $image);
    $stmt->execute();

    echo "<script>alert('Product added successfully');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> LANDERS </title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; }
        input, button { padding: 8px; margin: 5px 0; width: 300px; }
        button { width: 150px; cursor: pointer; }
    </style>
</head>
<body>
<header id="header">
    <div id="logo-container">
        <a href="Logo.html" id="logo">
            <img src="img/Logo.png" alt="Logo">
        </a>
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
</header><br>
<center>
<a href="#" id="explore">
    <img src="img/Explore.jpg" alt="Explore">
</a>
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required><br><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
        <input type="number" name="stock" placeholder="Stock" required><br><br>
        <input type="file" name="image" required><br><br>
        <button type="submit" name="add">Add Product</button>
    </form>
<br><br>
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
