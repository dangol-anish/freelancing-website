 <?php
require("../../config/helper/persistLogin.php");
?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="userLoginForm.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <form action="" method="POST">
      <h2>Login</h2>
      <label for="user_email">Email:</label>
      <input type="text" id="user_email" name="user_email" required />

      <label for="user_password">Password:</label>
      <input type="password" id="user_password" name="user_password" required />

      <input class="submit" type="submit" value="Submit" name="Submit" />
      <br>
      <p class="text">Don't have an account? <a href="http://localhost/freelancing-website/userAuth/userRegistration/userRegistrationForm.php">Register</a></p>
    </form>
  </body>
</html>
 
<?php

require("../../config/database/databaseConfig.php");

$errors = array();

try {
    if(isset($_POST["Submit"])) {
        $userEmail = $_POST["user_email"];
        $userPassword = $_POST["user_password"];

        $checkUserQuery = "select user_id, user_password, user_type from user where user_email= '$userEmail'";
        $checkUserResult = mysqli_query($connection, $checkUserQuery);

        if(mysqli_num_rows($checkUserResult) == 0) {
            $errors[] = "Your user email or password doesn't match";
        } else {
            $row = mysqli_fetch_assoc($checkUserResult);
            $storedUserPassword = $row["user_password"];
            $userId = $row["user_id"];
            $userType = $row["user_type"];

            if (password_verify($userPassword, $storedUserPassword)) {

                if($userType == "Freelancer"){
                session_start();
                $_SESSION["user_id"] = $userId;
                $_SESSION["login"] = true;
                $_SESSION["user_type"] = $userType;
                header("Location: http://localhost/freelancing-website/dashboard/freelancer/freelancerDashboard.php");

                }else if($userType == "Client"){

              session_start();
                $_SESSION["user_id"] = $userId;
                $_SESSION["login"] = true;
                $_SESSION["user_type"] = $userType;

                header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");

                }else if($userType = "Admin"){
                      session_start();
                $_SESSION["user_id"] = $userId;
                $_SESSION["login"] = true;
                $_SESSION["user_type"] = $userType;
                header("Location: http://localhost/freelancing-website/dashboard/admin/adminDashboard.php");


                }



              

             } else {
                $errors[] = "Your user email or password doesn't match";
            }
        }
    }
} catch(Exception $e) {
    $errors[] = $e->getMessage(); } if (!empty($errors)) { echo "
<div>
  "; echo "
  <ul>
    "; foreach ($errors as $error) { echo "
    <li>$error</li>
    "; } echo "
  </ul>
  "; echo "
</div>
"; } ?>
