<?php
session_start();
include("../config/database/databaseConfig.php");
include("links.php");

$fromUser = $_POST["fromUser"];
$toUser = $_POST["toUser"];
$message = $_POST["message"]; // Corrected variable name

$output = "";

$sql = "INSERT INTO messages (fromUser, toUser, message) VALUES ('$fromUser', '$toUser', '$message')"; // Enclose values in single quotes for SQL query

$result = mysqli_query($connection, $sql);


echo $result;
?>
