<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "freelancing_website";

$connection = mysqli_connect($host, $user, $password, $dbname);



if(!$connection){
    echo "Connection not established";
}