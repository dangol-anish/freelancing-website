 <?php

 require("../../../config/helper/persistLogin.php");
 ?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="clientForm.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <form method="POST" enctype="multipart/form-data">
      <h2>Client Verification Form</h2>

      <div class="files">
        <input
          class="inputfile"
          type="file"
          id="client_pan_photo"
          name="client_pan_photo"
          required
        />
        <label for="client_pan_photo">PAN Photo</label>

        <input
          class="inputfile"
          type="file"
          id="client_verification_photo"
          name="client_verification_photo"
          required
        />
        <label for="client_verification_photo">Verification Photo</label>
      </div>

      <input class="submit" type="submit" value="Submit" name="Submit" />
    </form>
  </body>
</html>
 
<?php
require("../../../config/database/databaseConfig.php");

$errors = array();

try {
    if(isset($_GET['user_id'])) {
        $userId = $_GET['user_id']; 
    } else {
        $errors[] = "No User ID";
    }

    if(!empty($userId)) {
        if(isset($_POST['Submit'])) {
            if(isset($_FILES['client_pan_photo']) && isset($_FILES['client_verification_photo'])) {
                $getUserIdQuery = "SELECT * FROM user WHERE user_id='$userId'";
                $getUserIdResult = mysqli_query($connection, $getUserIdQuery);

                if(mysqli_num_rows($getUserIdResult) == 0) {
                    $errors[] = "You are not an existing user";
                } else {
                    if(isset($_FILES['client_pan_photo'])) {
                        $clientPanPhoto = $_FILES['client_pan_photo']['name'];
                        $photoTempName = $_FILES['client_pan_photo']['tmp_name'];
                        $panFolder = "pan/".$clientPanPhoto;

                        if (move_uploaded_file($photoTempName, $panFolder)) {
                           
                        } else {
                            $errors[] = "Error moving identity photo file.";
                        }
                    } else {
                        $errors[] = "Identity photo file not provided.";
                    }

                    if(isset($_FILES['client_verification_photo'])) {
                        $clientVerificationPhoto = $_FILES['client_verification_photo']['name'];
                        $photoTempName = $_FILES['client_verification_photo']['tmp_name'];
                        $verificationFolder = "verification/".$clientVerificationPhoto;

                        if (move_uploaded_file($photoTempName, $verificationFolder)) {
                    
                        } else {
                            $errors[] = "Error moving verification photo file.";
                        }
                    } else {
                        $errors[] = "Verification photo file not provided.";
                    }

                    if(empty($errors)) {
                        $checkClientDataQuery = "SELECT * FROM client WHERE user_id = '$userId'";
                        $checkClientDataResult = mysqli_query($connection, $checkClientDataQuery);

                        if(mysqli_num_rows($checkClientDataResult) > 0) {
                            echo "Data already exists";
                        } else {
                            $insertClientData = "INSERT INTO client (client_pan_photo, client_verification_photo,  user_id)
                                VALUES ('$panFolder','$verificationFolder', '$userId')";

                            $clientData = mysqli_query($connection, $insertClientData);

                            if(!$clientData) {
                                throw new Exception("Records not inserted");
                            } else{
                                                                header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

                            }
                        }
                    }
                }
            } else {
                $errors[] = "Verification photo or CV file not provided.";
            }
        }
    }else{
       header("Location:http://localhost/freelancing-website/userAuth/userRegistration/userRegistrationForm.php ");
    }
} catch(Exception $e) {
    $errors[] = $e->getMessage();
}
?> 
