<?php
include("auth.php");
include("db.php");

if (isset($_POST["removeID"])) {
    $id = $_POST["removeID"];

    unset($_SESSION["cart"][$id]);

    header("Location: cart.php");
    exit();
}

if (isset($_POST["checkout"])) {

    $cart = $_SESSION["cart"] ?? [];

    if (!empty($cart)) {

        
        $stmt = $connect->prepare("
            INSERT INTO Orders (CustomerID, OrderDate, Status)
            VALUES (?, NOW(), 'Processing')
        ");

        $stmt->bind_param("i", $_SESSION["CustomerID"]);
        $stmt->execute();

        $orderID = $stmt->insert_id;

        
        foreach ($cart as $productID => $quantity) {

            // 1. Check stock
            $check = $connect->prepare("SELECT StockLevel FROM Products WHERE ProductID = ?");
            $check->bind_param("i", $productID);
            $check->execute();
            $result = $check->get_result()->fetch_assoc();

            if ($result["StockLevel"] < $quantity) {
                echo "Not enough stock available";
                exit();
            }

           
            $stmt = $connect->prepare("
                INSERT INTO OrderItems (OrderID, ProductID, Quantity)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iii", $orderID, $productID, $quantity);
            $stmt->execute();

            
            $updateStock = $connect->prepare("
                UPDATE Products 
                SET StockLevel = StockLevel - ? 
                WHERE ProductID = ?
            ");
            $updateStock->bind_param("ii", $quantity, $productID);
            $updateStock->execute();
        }

       
        unset($_SESSION["cart"]);

        
        header("Location: dashboard.php");
        exit();
    }
}

// Get cart
$cart = $_SESSION["cart"] ?? [];
$total = 0;

// Remove item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["removeIndex"])) {
    $index = $_POST["removeIndex"];

    unset($_SESSION["cart"][$index]);

    $_SESSION["cart"] = array_values($_SESSION["cart"]);

    header("Location: cart.php");
    exit();
}
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
            foreach ($cart as $productID => $quantity) {

                $stmt = $connect->prepare("SELECT * FROM Products WHERE ProductID = ?");
                $stmt->bind_param("i", $productID);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                if (!$product) {
                    continue; // skip if product doesn't exist
                }
                
                $total += $product["Price"] * $quantity;
            ?>

                <div class="cart-item">
                    <p><strong><?php echo $product["Name"]; ?></strong></p>
                    <p>£<?php echo $product["Price"]; ?></p>
                    <p>Quantity: <?php echo $quantity; ?></p>


                    <!-- Remove item -->
                    <form method="POST">
                        <input type="hidden" name="removeID" value="<?php echo $productID; ?>">
                        <button type="submit">Remove</button>
                    </form>
                </div>

            <?php } ?>

            <h3>Total: £<?php echo $total; ?></h3>
            <form method="POST">
                <button type="submit" name="checkout">Checkout</button>
            </form>
        <?php } ?>
    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>