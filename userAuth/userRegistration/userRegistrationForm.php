 <?php

 require("../../config/helper/persistLogin.php");
 ?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="userRegistration.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Form</title>
  </head>
  <body>
    <form method="POST" enctype="multipart/form-data">
      <h2>Create your account</h2>

      <div class="full-name">
        <label for="user_first_name">
          <p>First Name</p>
          <input
            type="text"
            id="user_first_name"
            name="user_first_name"
            required
          />
        </label>

        <label for="user_last_name"
          ><p>Last Name</p>
          <input
            type="text"
            id="user_last_name"
            name="user_last_name"
            required
          />
        </label>
      </div>

      <div class="priv">
        <label for="user_email">
          <p>Email</p>

          <input type="email" id="user_email" name="user_email" required />
        </label>

        <label for="user_phone_number"
          ><p>Phone Number</p>
          <input
            type="tel"
            id="user_phone_number"
            name="user_phone_number"
            pattern="[0-9]*"
            required
          />
        </label>
      </div>

      <label for="user_password"
        ><p>Password</p>
        <input
          type="password"
          id="user_password"
          name="user_password"
          required
        />
      </label>

      <label class="user-type">
        <p>User Type</p>
        <div class="user-type-inside">
          <input
            type="radio"
            id="freelancer"
            name="user_type"
            value="Freelancer"
            required
            checked
          />
          <label class="radio-label" for="freelancer">Freelancer</label>
        </div>

        <div class="user-type-inside">
          <input type="radio" id="client" name="user_type" value="Client" />

          <label class="radio-label" for="client">Client</label>
        </div>
      </label>

      <input
        type="file"
        id="user_photo"
        name="user_photo"
        class="inputfile"
        required
      />
      <label class="user-photo" for="user_photo">Choose a photo</label>

      <input class="submit" type="submit" value="Submit" name="Submit" />

      <p class="text">
        Already have an account?
        <a
          href="http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php"
          >Login</a
        >
      </p>
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



            $checkUserQuery = "SELECT user_id FROM user WHERE user_email='$userEmail' OR user_phone_number='$userPhoneNumber'";
            $checkUserResult = mysqli_query($connection, $checkUserQuery);
        
            if(mysqli_num_rows($checkUserResult) > 0 ) {
                $errors[] = "User with this email or phone number already exists.";
            }else if(strlen($userPhoneNumber) !== 10 ||!filter_var($userEmail, FILTER_VALIDATE_EMAIL) ){

 $errors[] = "Email or phone number format is not correct";
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

                $insertUserData = "INSERT INTO user (user_first_name, user_last_name, user_password, user_email, user_phone_number, user_type, user_photo, user_status)
                        VALUES ('$userFirstName', '$userLastName', '$hasedUserPassword', '$userEmail', '$userPhoneNumber', '$userType', '$folder', '0')";
                
                $userData=mysqli_query($connection,$insertUserData);
                

                if(!$userData){
    $errors[] = "Records not inserted";
} else {
$selectUserEmail = "SELECT user_id, user_type FROM user WHERE user_email = '$userEmail'";
$userEmailData = mysqli_query($connection, $selectUserEmail);

if(mysqli_num_rows($userEmailData) > 0) {
    $row = mysqli_fetch_assoc($userEmailData);
    $userId = $row['user_id'];
    $userType = $row['user_type'];





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
