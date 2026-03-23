<?php
$connect = mysqli_connect("localhost", "root", "", "glh");
if ($connect->connect_error) {
    die("Database connection failed" . $connect->connect_error);
}
?>