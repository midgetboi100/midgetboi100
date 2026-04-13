<?php 
//start the session - this is so that the user is kept signed in
session_start();
//connect the db
include("db.php");

//store success or error messages
$message = "";

//run if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

//get data
$email = $_POST['email'];
$password = $_POST['password'];

//sql query
$stmt = $connect->prepare("SELECT * FROM customers WHERE email = ?");

//attach email to query
$stmt->bind_param("s", $email);

//run query
$stmt->execute();

//results
$result = $stmt->get_result();

//does user exist?
if ($result->num_rows === 1) {
    //fetches user data
    $user = $result->fetch_assoc();

    //verify password is correct
    if (password_verify($password, $user['Password'])) {
        //stores user data in session
        $_SESSION['CustomerID'] = $user['CustomerID'];
        $_SESSION['Name'] = $user['Name'];

        //redirect to dashboard
        header("Location: dashboard.php");
        exit(); 
    }
    else {
        //password incorrect
        $message = "Incorrect password";
    }
//email not found or doesnt exist
}
else{
    $message = "Email not found";
}
}
?>

<!doctype html>
<html>
<head>
    <title>Login</title>
    <!-- link the register validation -->
    <script src="js/validateLogin.js"></script>
    <!-- link the styling file -->
    <link rel="stylesheet" href="css/loginstyle.css">
</head>
<body>
<div class="container">
<h2>Welcome back</h2>
<p class="subtitle">Sign in to your account</p>

<form method="POST" onsubmit="return validateLogin()">
    <!-- input email -->
    <input type="email" name="email" id="email" placeholder="Enter your email"><br><br>
    <!-- input password -->
    <input type="password" name="password" id="password" placeholder="Enter your password"><br><br>
    <!-- submit -->
    <button type="submit">Sign in</button>
</form>
<!-- display message -->
<p class="message"><?php echo $message; ?></p>

<!-- link to the signup page -->
<div class = "links">
Don't have an account? <a href="register.php">Sign up</a><br>
Are you a producer? <a href="producer_login.php">Sign in as a producer</a>
</div>
</div>
</body>
</html>