<?php
session_start();
include("db.php");

// Get all producers
$stmt = $connect->prepare("SELECT * FROM Producers");
$stmt->execute();
$producers = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Producers</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/producer_page.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <div class="container">
        <h2>Our Producers</h2>

        <div class="producer-list">

            <?php while ($row = $producers->fetch_assoc()) { ?>

                <div class="producer-card">

                    <h3><?php echo $row["Name"]; ?></h3>

                    <p>Location: <?php echo $row["Location"]; ?></p>

                    <p>Farming Method: <?php echo $row["FarmingMethod"]; ?></p>

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>