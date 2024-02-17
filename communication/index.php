<?php
session_start();

include("../config/database/databaseConfig.php");
include("links.php");

if($_SESSION["user_id"]){
    header("location: chatbox.php");
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
               <div class="modal-header">
                <h4>
                    Please Select your account
                </h4>

        </div>
           <div class="modal-body">
            <ol>
            <?php
            $users = mysqli_query($connection, "select * from user")
            or die("Failed to query database");

            while($user = mysqli_fetch_assoc($users))
            {
                echo '<li>
              <a href="index.php?userId=' . $user["user_id"] . '">' . $user["user_first_name"] . '</a>
          </li>';
            }
            ?>
            </ol>
        

        </div>

        </div>
        
    </div>
</body>
</html>