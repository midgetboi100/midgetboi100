<?php 
include("auth.php");
include("db.php");

if (!isset($_SESSION["ProducerID"])) {
    header("Location: producer_login.php");
    exit();
}

$stmt = $connect->prepare("SELECT Name FROM Producers WHERE ProducerID = ?");
$stmt->bind_param("i", $_SESSION["ProducerID"]);
$stmt->execute();
$result = $stmt->get_result();
$producer = $result->fetch_assoc();
$stmt->close();

if (isset($_POST["addProduct"])) {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $stmt = $connect->prepare("INSERT INTO Products (Name, Description, Price, StockLevel, ProducerID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $_SESSION["ProducerID"]);
    $stmt->execute();
    $stmt->close(); 

    header("Location: producer_dashboard.php");
    exit();
}

$stnt = $connect->prepare("SELECT * FROM Products WHERE ProducerID = ?");
$stmt->bind_param("i", $_SESSION["ProducerID"]);
$stmt->execute();
$products = $stmt->get_result();
$stmt->close();

?>