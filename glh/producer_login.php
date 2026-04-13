<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $connect->prepare("SELECT * FROM Producers WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $producer = $result->fetch_assoc();

        if (password_verify($password, $producer["Password"])) {
            $_SESSION["ProducerID"] = $producer["ProducerID"];
            $_SESSION["ProducerName"] = $producer["Name"];

            header("Location: producer_dashboard.php");
            exit();

        } else {
            $message = "Incorrect password";
        }

    } else {
        $message = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Producer Login</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/producer_login.css">
</head>

<body>

<?php include("navbar.php"); ?>

<div class="main-content">

    <div class="container">

        <h2>Producer Login</h2>
        <p class="subtitle">Sign in to manage your products</p>

        <form method="POST">

            <input type="email" name="email" placeholder="Enter your email" required>

            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Login</button>

        </form>

        <p class="message"><?php echo $message; ?></p>

    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>