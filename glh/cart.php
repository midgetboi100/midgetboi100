<?php
include("auth.php");
include("db.php");

// Get cart
$cart = $_SESSION["cart"] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <div class="container">
        <h2>Your Cart</h2>

        <?php if (empty($cart)) { ?>
            <p>Your cart is empty</p>
        <?php } else { ?>

            <?php
            foreach ($cart as $index => $productID) {

                $stmt = $connect->prepare("SELECT * FROM Products WHERE ProductID = ?");
                $stmt->bind_param("i", $productID);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                $total += $product["Price"];
            ?>

                <div class="cart-item">
                    <p><strong><?php echo $product["Name"]; ?></strong></p>
                    <p>£<?php echo $product["Price"]; ?></p>

                    <!-- Remove item -->
                    <form method="POST">
                        <input type="hidden" name="removeIndex" value="<?php echo $index; ?>">
                        <button type="submit">Remove</button>
                    </form>
                </div>

            <?php } ?>

            <h3>Total: £<?php echo $total; ?></h3>

        <?php } ?>
    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>