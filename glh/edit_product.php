<?php
include("auth.php");
include("db.php");

$id = $_GET["id"];

$stmt = $connect->prepare("SELECT * FROM Products WHERE ProductID = ? AND ProducerID = ?");
$stmt->bind_param("ii", $id, $_SESSION["ProducerID"]);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $desc = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $stmt = $connect->prepare("
        UPDATE Products 
        SET Name=?, Description=?, Price=?, StockLevel=?
        WHERE ProductID=?
    ");

    $stmt->bind_param("ssdii", $name, $desc, $price, $stock, $id);
    $stmt->execute();

    header("Location: producer_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

<div class="container">
    <h2>Edit Product</h2>

    <form method="POST">

        <input type="text" name="name" value="<?php echo $product["Name"]; ?>" required>

        <textarea name="description" required><?php echo $product["Description"]; ?></textarea>

        <input type="number" step="0.01" name="price" value="<?php echo $product["Price"]; ?>" required>

        <input type="number" name="stock" value="<?php echo $product["StockLevel"]; ?>" required>

        <button type="submit">Update</button>

    </form>
</div>

</body>
</html>