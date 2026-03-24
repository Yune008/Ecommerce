<?php
$conn = new mysqli("localhost", "root", "", "Landersdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$product_name = $_POST['product_name'];
$price        = $_POST['price'];
$quantity     = $_POST['quantity'];
$name         = $_POST['name'];
$contact      = $_POST['contact_num'];

$total = $price * $quantity;

$sql = "INSERT INTO orders (product_name, quantity, total, name, contact_num)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sidss", $product_name, $quantity, $total, $name, $contact);
$stmt->execute();

header("Location: orders.php");
exit();
?>
