<?php
session_start();
include("../config/database/databaseConfig.php");
include("links.php");

$job_id = $_GET["job_id"];
$fromUser = $_POST["fromUser"];
$toUser = $_POST["toUser"];
$message = $_POST["message"]; // Corrected variable name

$output = "";

$sql = "INSERT INTO messages (fromUser, toUser, job_id, message) VALUES ('$fromUser', '$toUser', '$job_id', '$message')"; // Enclose values in single quotes for SQL query

$result = mysqli_query($connection, $sql);


echo $result;
?>
