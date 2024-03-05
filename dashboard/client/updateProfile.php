<?php
include("../../config/database/databaseConfig.php");

$userFirstName = "";
$userLastName = "";
$errors = array();
$panFolder = "";
$verificationFolder = "";



if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

if ($_SESSION["user_type"] != "Client") {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

$userId = $_SESSION["user_id"];
$userType = $_SESSION["user_type"];

$userDataQuery = "SELECT user_first_name, user_last_name, user_email, user_phone_number, user_photo FROM user WHERE user_id='$userId'";
$clientDataQuery = "select client_pan_photo, client_verification_photo from client where user_id='$userId'";
$userDataResult = mysqli_query($connection, $userDataQuery);
$clientDataResult = mysqli_query($connection, $clientDataQuery);

if(mysqli_num_rows($userDataResult) > 0) {
    $userData = mysqli_fetch_assoc($userDataResult);
    $userFirstName = $userData['user_first_name'];
    $userLastName = $userData['user_last_name'];
    $userEmail = $userData["user_email"];
    $userPhone = $userData["user_phone_number"];
    $userPhoto = $userData["user_photo"];
    if(mysqli_num_rows($clientDataResult)>0){
        $clientData = mysqli_fetch_assoc($clientDataResult);
        $clientPanPhoto = $clientData["client_pan_photo"];
        $clientVerificationPhoto = $clientData["client_verification_photo"];
    }

} else {
    $errors[] = "User data not found.";
}

if (isset($_POST["update"])) {
    $userFirstName = $_POST["userFirstName"];
    $userLastName = $_POST["userLastName"];
     $userEmail = $_POST["user_email"];
    $userPhone = $_POST["user_phone_number"];
    $currentPassword = $_POST["userPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $clientPanPhoto = $_FILES['client_pan_photo']['name'];
    $clientVerificationPhoto = $_FILES['client_verification_photo']['name'];
    $tempName1 = $_FILES['client_pan_photo']['tmp_name'];
    $tempName2 = $_FILES['client_verification_photo']['tmp_name'];
    $panFolder = "pan/" . $clientPanPhoto;
    $verificationFolder = "verification/" . $clientVerificationPhoto;

    $fileName = $_FILES['user_photo']['name'];
    $tempName = $_FILES['user_photo']['tmp_name'];
    $folder = "pics/"; 
    $filePath = $folder . $fileName; 

    if (move_uploaded_file($tempName, $filePath)) {
    } else {
        $errors[] = "Error moving file.";
    }

    if (move_uploaded_file($tempName1, $panFolder)) {
    } else {
        $errors[] = "Error moving identity photo file.";
    }

    if (move_uploaded_file($tempName2, $verificationFolder)) {
    } else {
        $errors[] = "Error moving verification photo file.";
    }

    $getUserPasswordQuery = "SELECT user_password FROM user WHERE user_id='$userId'";
    $getUserPasswordResult = mysqli_query($connection, $getUserPasswordQuery);

    if (mysqli_num_rows($getUserPasswordResult) > 0) {
        $row = mysqli_fetch_assoc($getUserPasswordResult);
        $storedUserPassword = $row["user_password"];
        if (password_verify($currentPassword, $storedUserPassword)) {
            $updateProfileQuery = "UPDATE user SET user_first_name='$userFirstName', user_last_name='$userLastName' , user_email='$userEmail', user_phone_number='$userPhone', user_photo='$filePath' WHERE user_id='$userId'";
            $updateProfileResult =mysqli_query($connection, $updateProfileQuery);

            $updateClientQuery = "UPDATE client SET client_pan_photo='$panFolder', client_verification_photo='$verificationFolder' WHERE user_id='$userId'";
            $updateClientResult = mysqli_query($connection, $updateClientQuery);
            if ($updateProfileResult && $updateClientResult) {
               header("Location:http://localhost/freelancing-website/dashboard/client/clientProfile.php ");
            } else {
                echo "Error updating profile.";
            }
        }
    }
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}
?>

<link rel="stylesheet" href="clientDashboard.css">
<div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Update Profile</h2>
        <form action="" class="displayProfileForm" method="POST" enctype="multipart/form-data">
            <div class="update-profile-first">
                <div class="wrapper">
                    <input type="file" name="user_photo" class="my_file">
                    <img class="user_photo" value='<?php echo $userPhoto?>'  src='<?php echo $userPhoto?>' alt="">
                </div>
                <div class="full-name">
                    <div class="input-margin">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="userFirstName" value="<?php echo $userFirstName; ?>"><br>
                    </div>
                    <div class="input-margin">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="userLastName" value="<?php echo $userLastName; ?>"><br>
                    </div>
                    <div class="input-margin">
                        <label for="user_email">Email</label>
                        <input type="email" id="user_email" name="user_email" value="<?php echo $userEmail?>" required /><br>
                    </div>
                    <div class="input-margin">
                        <label for="user_phone_number">Phone Number</label>
                        <input type="tel" id="user_phone_number" name="user_phone_number" pattern="[0-9]*" value="<?php echo $userPhone?>" required />
                    </div>
                </div>
            </div>
           
            <div class="files-display">
                <input
          class="inputfile"
          type="file"
          id="client_pan_photo"
          name="client_pan_photo"
          value="<?php echo $clientPanPhoto?>"
          required
        />
        <label for="client_pan_photo">PAN Photo</label>

        <input
          class="inputfile"
          type="file"
          id="client_verification_photo"
          name="client_verification_photo"
        value="<?php echo $clientVerificationPhoto?>"
          required
        />
        <label for="client_verification_photo">Verification Photo</label>
            </div>

            <div class="password-style">
                <div class="password-style-inner">
                    <label class="small-text" for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="userPassword" value="" required><br>
                </div>
              
            </div>
    
            <input class="logout" type="submit" value="Update Profile" name="update">
        </form>
    </div>
</div>
