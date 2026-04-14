<?php 
include("db.php");
include("auth.php");


//get user info
$stmt = $connect->prepare("SELECT Name, LoyaltyPoints, ProfileImage FROM Customers WHERE CustomerID = ?");
$stmt->bind_param("i", $_SESSION["CustomerID"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

//get orders
$orderStmt = $connect->prepare("SELECT * FROM Orders WHERE CustomerID = ?");
$orderStmt->bind_param("i", $_SESSION["CustomerID"]);
$orderStmt->execute();
$orders = $orderStmt->get_result();
$orderStmt->close();

//profile image upload
if (isset($_POST["uploadPFP"])) {
    $imageName = $_FILES["ProfileImage"]["name"];
    $tempName = $_FILES["ProfileImage"]["tmp_name"];

    $path = "uploads/" . time() . "_" . $imageName;
    move_uploaded_file($tempName, $path);

    $stmt = $connect->prepare("UPDATE Customers SET ProfileImage = ? WHERE CustomerID = ?");
    $stmt->bind_param("si", $path, $_SESSION["CustomerID"]);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/dashboard.css">

    
</head>
<body>
<?php include("navbar.php"); ?>
<div class ="main-content">
    <div class="dashboard">
        <div class="profile_section">
            <img src="<?php echo $user["ProfileImage"] ?? "default.png"; ?>">
            <h2><?php echo $user["Name"]; ?></h2>
            <p>Loyalty Points: <?php echo $user["LoyaltyPoints"]; ?></p>

            <!-- upload new pfp -->
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="ProfileImage" required>
                <button type="submit" name="uploadPFP">Upload</button>
            </form>
        </div>
        <div class="section">
            <h3>Current Orders</h3>

            <?php while ($order = $orders->fetch_assoc()) { ?>

                <div class="order">

                    <p>Order #<?php echo $order["OrderID"]; ?></p>
                    <p>Status: <?php echo $order["Status"]; ?></p>
                    <p>Date: <?php echo $order["OrderDate"]; ?></p>

                    <?php
                    $stmt = $connect->prepare("
                        SELECT Products.Name, OrderItems.Quantity
                        FROM OrderItems
                        JOIN Products ON OrderItems.ProductID = Products.ProductID
                        WHERE OrderItems.OrderID = ?
                    ");
                    $stmt->bind_param("i", $order["OrderID"]);
                    $stmt->execute();
                    $items = $stmt->get_result();
                    ?>

                    <div class="order-items">
                        <?php while ($item = $items->fetch_assoc()) { ?>
                            <p>
                                <?php echo $item["Name"]; ?> 
                                (x<?php echo $item["Quantity"]; ?>)
                            </p>
                        <?php } ?>
                    </div>

                </div>

            <?php } ?>
        </div>
        <!-- Change password -->
        <div class="section">
            <h3>Change Password</h3>

            <form method="POST">
                <input type="password" name="newPassword" placeholder="New Password">
                <button type="submit">Update</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $newPass = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
                $update = $connect->prepare("UPDATE Customers SET Password = ? WHERE CustomerID = ?");
                $update->bind_param("si", $newPass, $_SESSION["CustomerID"]);
                $update->execute();

                echo "<p>Password has been changed</p>";
            }

            ?>

        </div>

    </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>