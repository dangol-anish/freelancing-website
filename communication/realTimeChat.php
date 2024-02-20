<?php
session_start();

include("../config/database/databaseConfig.php");
include("links.php");

$fromUser = $_POST["fromUser"];
$toUser = $_POST["toUser"];
$output = "";
$job_id = $_GET["job_id"];

$sql = "SELECT * FROM messages WHERE ((fromUser = '".$fromUser."' AND toUser = '".$toUser."') OR 
        (fromUser = '" . $toUser . "' AND toUser = '".$fromUser."')) AND job_id=$job_id";

$chats = mysqli_query($connection, $sql) or die(mysqli_error($connection));

while($chat = mysqli_fetch_assoc($chats)){
    if($chat["fromUser"] == $_SESSION["user_id"]){
        $output.= "
            <div style='text-align: right;'>
                <p style='background-color: lightblue; word-wrap: break-word; display: inline-block; padding: 5px; border-radius: 10px; max-width: 70%;'> "
                .$chat["Message"]."
                </p>
            </div>";
    } else {
        $output.= "
            <div style='text-align: left;'>
                <p style='background-color: yellow; word-wrap: break-word; display: inline-block; padding: 5px; border-radius: 10px; max-width: 70%;'> "
                .$chat["Message"]."
                </p>
            </div>";
    }
}
echo $output;
?>
