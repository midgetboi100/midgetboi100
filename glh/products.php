<?php
include("auth.php");
include("db.php");

//Get all products + producer info
$stmt = $connect->prepare("
    SELECT Products.*, Producers.Name AS ProducerName, Producers.FarmingMethod
    FROM Products
    JOIN Producers ON Products.ProducerID = Producers.ProducerID
");

$stmt->execute();
$products = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productID"])) {

    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($_SESSION["cart"][$productID])) {
        $_SESSION["cart"][$productID] += $quantity;
    } else {
        $_SESSION["cart"][$productID] = $quantity;
    }

    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/products.css">
</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <div class="products-wrapper">
        <h2>Available Products</h2>

        <div class="products-container">
            <?php while ($row = $products->fetch_assoc()) { ?>

                <div class="product-card">
                    <h3><?php echo $row["Name"]; ?></h3>
                    <p><?php echo $row["Description"]; ?></p>
                    <p><strong>£<?php echo $row["Price"]; ?></strong></p>
                    <p>From: <?php echo $row["ProducerName"]; ?></p>
                    <p>Method: <?php echo $row["FarmingMethod"]; ?></p>
                    <?php if ($row["StockLevel"] > 0) { ?>
                        <p>Stock: <?php echo $row["StockLevel"]; ?></p>
                    <?php } else { ?>
                        <p style="color:red;"><strong>Out of Stock</strong></p>
                    <?php } ?>

                    <form method="POST">
                        <input type="hidden" name="productID" value="<?php echo $row["ProductID"]; ?>">

                        <?php if ($row["StockLevel"] > 0) { ?>

                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $row["StockLevel"]; ?>">
                            <button type="submit">Add to Cart</button>

                        <?php } else { ?>

                            <button type="button" disabled style="background: grey;">
                                Out of Stock
                            </button>

                        <?php } ?>

                    </form>
                </div>

            <?php } ?>
        </div>

    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>