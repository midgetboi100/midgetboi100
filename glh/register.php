<?php 
//connect to database
include("db.php");
//store message for either error or success
$message = "";
//run if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

//get data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$address = $_POST['address'];

//hash password
$hashedpassword = password_hash($password, PASSWORD_DEFAULT);

//check if the email exists already
$check = $connect->prepare("SELECT * FROM customers WHERE email = ?");

//attach the email to the query
$check->bind_param('s', $email);

//run
$check->execute();

//does email exist?
$result = $check->get_result();

//email exists
if ($result->num_rows > 0) {
    $message = "Email already exists!";
}
//email doesnt exist
else {
    //query to insert new user
    $stmt = $connect->prepare("INSERT INTO customers
    (name, email, password, address) 
    VALUES (?,?,?,?)"
    );

    //attach data
    $stmt->bind_param("ssss", $name, $email, $hashedpassword, $address);
    //run
    if ($stmt->execute()) {
        $message = "Registration successful. You can now log in!";
    } else {
        $message = "Error: You could not be registered!";
    }
}
}
?>

<!doctype html>
<html>
<head>
    <title>Register</title>
    <!-- link the register validation -->
    <script src="js/validateRegister.js"></script>
    <!-- link the styling file -->
    <link rel="stylesheet" href="css/registerstyle.css">
</head>
<body>
<div class="container">
<h2>Create an account</h2>
<p class="subtitle">Sign up to get started</p>

<form method="POST" onsubmit="return validateRegister()">
    <!-- input name -->
    <input type="text" name="name" id="name" placeholder="Full Name"><br><br>
    <!-- input email -->
    <input type="email" name="email" id="email" placeholder="Email"><br><br>
    <!-- input password -->
    <input type="password" name="password" id="password" placeholder="Password"><br><br>
    <!-- input address -->
    <input type="text" name="address" id="address" placeholder="Address"><br><br>
    <!-- agree to t&cs -->
    <div class="checkbox">
    
    <input type="checkbox" required> I agree to the <a href= "terms.php">Terms & Conditions</a>
    
    </div>
    <!-- submit -->
    <button type="submit">Sign up</button>
</form>
<!-- display message -->
<p class="message"><?php echo $message; ?></p>

<!-- link to the login page -->
<div class = "links">
Already have an account? <a href="login.php">Sign in</a><br>
Are you a producer? <a href="#">Sign in as a producer</a>
</div>
</div>
</body>
</html>