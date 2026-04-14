<?php
session_start();
include("db.php");

// Get some products (e.g. latest 4)
$stmt = $connect->prepare("
    SELECT Products.*, Producers.Name AS ProducerName
    FROM Products
    JOIN Producers ON Products.ProducerID = Producers.ProducerID
    LIMIT 4
");

$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">

</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <!-- Hero section -->
    <div class="hero">
        <h1>Welcome to Greenfield Local Hub</h1>
        <p>Fresh local products from trusted producers</p>

        <a href="products.php" class="btn">Browse Products</a>
    </div>

    <!-- Featured products -->
    <div class="section">
        <h2>Featured Products</h2>

        <div class="products">

            <?php while ($row = $products->fetch_assoc()) { ?>

                <div class="card">
                    <h3><?php echo $row["Name"]; ?></h3>
                    <p><?php echo $row["Description"]; ?></p>
                    <p>£<?php echo $row["Price"]; ?></p>
                    <p>From: <?php echo $row["ProducerName"]; ?></p>
                </div>

            <?php } ?>

        </div>
    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>