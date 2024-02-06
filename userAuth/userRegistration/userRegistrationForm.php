<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
</head>
<body>
    <h2>User Registration Form</h2>


    <form  method="POST" enctype="multipart/form-data">
        <label for="user_first_name">First Name:</label><br />
        <input type="text" id="user_first_name" name="user_first_name" required /><br /><br />

        <label for="user_last_name">Last Name:</label><br />
        <input type="text" id="user_last_name" name="user_last_name" required /><br /><br />

        <label for="user_password">Password:</label><br />
        <input type="password" id="user_password" name="user_password" required /><br /><br />

        <label for="user_email">Email:</label><br />
        <input type="email" id="user_email" name="user_email" required /><br /><br />

        <label for="user_phone_number">Phone Number:</label><br />
        <input type="tel" id="user_phone_number" name="user_phone_number" pattern="[0-9]*" required /><br /><br />

        <label>User Type:</label><br />
        <input type="radio" id="freelancer" name="user_type" value="Freelancer" required />
        <label for="freelancer">Freelancer</label><br />
        <input type="radio" id="client" name="user_type" value="Client" />
        <label for="client">Client</label><br /><br />

        <label for="user_photo">Photo URL:</label><br />
        <input type="file" id="user_photo" name="user_photo" required /><br /><br />

        <input type="submit" value="Submit" name="Submit" />
    </form>
</body>
</html>



    <?php

    require("../../config/database/databaseConfig.php");


    $errors = array();

    try {
  
        if(isset($_POST['Submit'])){
         
            $userEmail = $_POST['user_email'];
            $userPhoneNumber = $_POST['user_phone_number'];
           


            $checkEmailQuery = "SELECT * FROM user WHERE user_email='$userEmail'";
            $checkEmailResult = mysqli_query($connection, $checkEmailQuery);
            $checkPhoneNumberQuery = "SELECT * FROM user WHERE user_phone_number='$userPhoneNumber'";
            $checkPhoneNumberResult = mysqli_query($connection, $checkPhoneNumberQuery);
            if(mysqli_num_rows($checkEmailResult) > 0 || mysqli_num_rows($checkPhoneNumberResult) >0) {
                $errors[] = "User with this email or phone number already exists.";
            }
            
            else {


            $userFirstName = $_POST['user_first_name'];
            $userLastName = $_POST['user_last_name'];
            $userPassword = $_POST['user_password'];

            $hasedUserPassword =  password_hash($userPassword, PASSWORD_DEFAULT);
         
            $userType = $_POST['user_type'];


            $fileName = $_FILES['user_photo']['name'];
            $tempName = $_FILES['user_photo']['tmp_name'];
            $folder="pics/".$fileName;
            if (move_uploaded_file($tempName, $folder)) {

            } else {
                $errors[] = "Error moving file.";
            }

                $insertUserData = "INSERT INTO user (user_first_name, user_last_name, user_password, user_email, user_phone_number, user_type, user_photo)
                        VALUES ('$userFirstName', '$userLastName', '$hasedUserPassword', '$userEmail', '$userPhoneNumber', '$userType', '$folder')";
                
                $userData=mysqli_query($connection,$insertUserData);
                

                if(!$userData){
    $errors[] = "Records not inserted";
} else {
$selectUserEmail = "SELECT user_id FROM user WHERE user_email = '$userEmail'";
$userEmailData = mysqli_query($connection, $selectUserEmail);

if(mysqli_num_rows($userEmailData) > 0) {
    $row = mysqli_fetch_assoc($userEmailData);
    $userId = $row['user_id'];
   


    if ($userType === 'Client') {
        header("Location: clientRegistration/clientForm.php?user_id=$userId");
        exit(); 
    } elseif ($userType === 'Freelancer') {
        header("Location: freelancerRegistration/freelancerForm.php?user_id=$userId");
        exit(); 
    } else {
   
        $errors[] = "Unknown user type.";
    }

    } else {
    $errors[] = "Something went wrong";
}
}

            }
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }


    if (!empty($errors)) {
        echo "<div>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
?>
