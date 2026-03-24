 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&R Membership Shopping</title>
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
        <div id="header">
        <nav>
    <ul id="nav">
        <div id="logo">
        <a href="index.html"><img id="logo" src="img/logooo.png" width="30%" height="30%" alt="logo"></a>
        </div>
        <li><a href="Home.php">Home</a></li>
        <li><a href="About.html">About</a></li>
        <li><a href="Orders.php">Orders</a></li>
        <li><a href="Cart.php">Cart</a></li>
        <li><a href="Admin.php" class="admin-btn">Admin</a></li>
    </ul>
        </div></div></nav>

<?php
include "database.php"; 

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<section>
    <div class="product-container">
    <?php
    if ($result->num_rows > 0) {
        while ($products = $result->fetch_assoc()) 
    {
    ?>
        <div class="product-grid">
            <div class="product-card">
                <img src="images/<?php echo htmlspecialchars ($products['image']); ?>">
            </div>
            <div class="product-label">
                <p><b>Product Name:</b> <?php echo htmlspecialchars ($products['product_name']); ?>
                <b>Price:</b> ₱<?php echo number_format( $products['price']); ?>
                <b>Stocks Available:</b> <?php echo $products['stocks']; ?></p>
            </div>
            <div class="cart-btn">
                <button class="add-cart">Add to Cart</button>
            </div>
            <div class="buy-btn">
            <button class="buy-now"
            onclick="openbuynow(
                <?php echo $products['id']; ?>,
                '<?php echo addslashes($products['product_name']); ?>',
                <?php echo $products['price']; ?>,
                <?php echo $products['stocks']; ?>,
                '<?php echo $products['image']; ?>'
            )">
            Buy Now
            </button></div>
        </div>
    <?php
        }
    } else {
        echo "<p>No products available.</p>";
    }
    $conn->close();
    ?>
    </div>
</section>

<div class="modal fade" id="buynowmodal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Buy Now</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="purchase.php">

                    <input type="hidden" name="id" id="modalproductid">

                    <img id="modalproductimage"
                         class="img-fluid mb-3"
                         style="max-height:200px; object-fit:contain;">

                    <p><b>Product:</b> <span id="modalproductname"></span></p>
                    <p><b>Price:</b> ₱<span id="modalprice"></span></p>
                    <p><b>Available Stocks:</b> <span id="modalstocks"></span></p>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number"
                               id="modalquantity"
                               name="quantity"
                               class="form-control"
                               value="1"
                               min="1"
                               oninput="updateTotal()">
                    </div>

                    <p>
                        <b>Total Amount:</b>
                        ₱<span id="modaltotalamount">0.00</span>
                    </p>

                    <button type="submit" class="btn btn-success w-100">
                        Confirm Purchase
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
    
<footer>
    <div id="footer">
        Copyright @ 2025 S&R Membership Shopping. All rights reserved.
    </div></footer>
    <script src="js\bootstrap.bundle.min.js"></script>
<script>
let currentPrice = 0;

function openbuynow(id, name, price, stocks, image) {

    currentPrice = price;

    document.getElementById("modalproductid").value = id;
    document.getElementById("modalproductname").innerText = name;
    document.getElementById("modalprice").innerText = price.toFixed(2);
    document.getElementById("modalstocks").innerText = stocks;

    // SHOW IMAGE FROM VARCHAR
    document.getElementById("modalproductimage").src = "images/" + image;

    // RESET QUANTITY
    document.getElementById("modalquantity").value = 1;

    updateTotal();

    // SHOW MODAL
    let modal = new bootstrap.Modal(
        document.getElementById("buynowmodal")
    );
    modal.show();
}

function updateTotal() {
    let qty = document.getElementById("modalquantity").value;
    let total = currentPrice * qty;

    document.getElementById("modaltotalamount").innerText =
        total.toFixed(2);
}
</script>

</body>
</html>