<?php
include("auth.php");
include("db.php");


$stmt = $connect->prepare("SELECT Name FROM Producers WHERE ProducerID = ?");
$stmt->bind_param("i", $_SESSION["ProducerID"]);
$stmt->execute();
$producer = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (isset($_POST["addProduct"])) {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];


    $stmt = $connect->prepare("
        INSERT INTO Products (Name, Description, Price, StockLevel, ProducerID)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $_SESSION["ProducerID"]);
    $stmt->execute();
    $stmt->close();

    header("Location: producer_dashboard.php");
    exit();
}

if (isset($_POST["deleteID"])) {
    $id = $_POST["deleteID"];

    $stmt = $connect->prepare("DELETE FROM Products WHERE ProductID = ? AND ProducerID = ?");
    $stmt->bind_param("ii", $id, $_SESSION["ProducerID"]);
    $stmt->execute();
}

$stmt = $connect->prepare("SELECT * FROM Products WHERE ProducerID = ?");
$stmt->bind_param("i", $_SESSION["ProducerID"]);
$stmt->execute();
$products = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Producer Dashboard</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/producer.css">
    <link rel="stylesheet" href="css/footer.css">
    
</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <div class="dashboard">

        <div class="section">
            <h2>Welcome, <?php echo $producer["Name"]; ?></h2>
        </div>

        <div class="section">
            <h3>Add Product</h3>

            <form method="POST">
                <input type="text" name="name" placeholder="Product Name" required>
                
                <textarea name="description" placeholder="Product Description" required></textarea>
                
                <input type="number" step="0.01" name="price" placeholder="Price (£)" required>
                
                <input type="number" name="stock" placeholder="Stock Quantity" required>

                <button type="submit" name="addProduct">Add Product</button>
            </form>
        </div>

        <div class="section">
            <h3>Your Products</h3>

            <?php while ($row = $products->fetch_assoc()) { ?>

                <div class="product">
                    <p><strong><?php echo $row["Name"]; ?></strong></p>
                    <p><?php echo $row["Description"]; ?></p>
                    <p>£<?php echo $row["Price"]; ?></p>
                    <p>Stock: <?php echo $row["StockLevel"]; ?></p>
                </div>
                <form method="GET" action="edit_product.php">
                    <input type="hidden" name="id" value="<?php echo $row["ProductID"]; ?>">
                    <button type="submit">Edit</button>
                </form>
                <form method="POST">
                    <input type="hidden" name="deleteID" value="<?php echo $row["ProductID"]; ?>">
                    <button type="submit">Delete</button>
                </form>


            <?php } ?>
        </div>

    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>