<?php
$con = mysqli_connect("localhost", "root", "", "ams"); // Change 'ams' to your database name

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
