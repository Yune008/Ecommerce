<?php
include "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Get product info
    $sql = "SELECT product_price, product_stock 
            FROM products 
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found");
    }

    $price = $product['product_price'];
    $current_stock = $product['product_stock'];
    $totalprice = $price * $quantity;

    if ($quantity > $current_stock) {
        die("Order exceeds available stock");
    }

    $new_stock = $current_stock - $quantity;

    // Update stock
    $sql_update = "UPDATE products 
                   SET product_stock = ? 
                   WHERE product_id = ?";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $new_stock, $id);
    $stmt_update->execute();

    echo "Order placed successfully! Total: ₱" . number_format($totalprice, 2);
}
?>
