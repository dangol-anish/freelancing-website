
<link rel="stylesheet" href="freelancerDashboard.css">

<?php
include("../../config/database/databaseConfig.php");

$userFirstName = "";
$userLastName = "";
$freelancerBio = "";
$errors = array();
$identityFolder = "";
$verificationFolder = "";
$cvFolder = "";

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

if ($_SESSION["user_type"] != "Freelancer") {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

$userId = $_SESSION["user_id"];
$userType = $_SESSION["user_type"];

$userDataQuery = "SELECT user_first_name, user_last_name, user_email, user_phone_number, user_photo FROM user WHERE user_id='$userId'";
$freelancerDataQuery = "SELECT freelancer_identity_photo, freelancer_verification_photo, freelancer_cv FROM freelancer WHERE user_id='$userId'";
$userDataResult = mysqli_query($connection, $userDataQuery);
$freelancerDataResult = mysqli_query($connection, $freelancerDataQuery);

if (mysqli_num_rows($userDataResult) > 0) {
    $userData = mysqli_fetch_assoc($userDataResult);
    $userFirstName = $userData['user_first_name'];
    $userLastName = $userData['user_last_name'];
    $userEmail = $userData["user_email"];
    $userPhone = $userData["user_phone_number"];
    $userPhoto = $userData["user_photo"];
    if (mysqli_num_rows($freelancerDataResult) > 0) {
        $freelancerData = mysqli_fetch_assoc($freelancerDataResult);
        $freelancerIdentityPhoto = $freelancerData["freelancer_identity_photo"];
        $freelancerVerificationPhoto = $freelancerData["freelancer_verification_photo"];
        $freelancerCv = $freelancerData["freelancer_cv"];
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
    $freelancerIdentityPhoto = $_FILES['freelancer_identity_photo']['name'];
    $freelancerVerificationPhoto = $_FILES['freelancer_verification_photo']['name'];
    $freelancerCv = $_FILES['freelancer_cv']['name'];
    $tempName1 = $_FILES['freelancer_identity_photo']['tmp_name'];
    $tempName2 = $_FILES['freelancer_verification_photo']['tmp_name'];
    $tempName3 = $_FILES['freelancer_cv']['tmp_name'];
    $identityFolder = "identity/" . $freelancerIdentityPhoto;
    $verificationFolder = "verification/" . $freelancerVerificationPhoto;
    $cvFolder = "cv/" . $freelancerCv;
    $fileName = $_FILES['user_photo']['name'];
    $tempName = $_FILES['user_photo']['tmp_name'];
    $folder = "pics/";
    $filePath = $folder . $fileName;

    if (move_uploaded_file($tempName, $filePath)) {
    } else {
        $errors[] = "Error moving file.";
    }

    if (move_uploaded_file($tempName1, $identityFolder)) {
    } else {
        $errors[] = "Error moving identity photo file.";
    }

    if (move_uploaded_file($tempName2, $verificationFolder)) {
    } else {
        $errors[] = "Error moving verification photo file.";
    }
    if (move_uploaded_file($tempName3, $cvFolder)) {
    } else {
        $errors[] = "Error moving verification photo file.";
    }

    $getUserPasswordQuery = "SELECT user_password FROM user WHERE user_id='$userId'";
    $getUserPasswordResult = mysqli_query($connection, $getUserPasswordQuery);

    if (mysqli_num_rows($getUserPasswordResult) > 0) {
        $row = mysqli_fetch_assoc($getUserPasswordResult);
        $storedUserPassword = $row["user_password"];
        if (password_verify($currentPassword, $storedUserPassword)) {
            $updateProfileQuery = "UPDATE user SET user_first_name='$userFirstName', user_last_name='$userLastName', user_email='$userEmail', user_phone_number='$userPhone', user_photo='$filePath' WHERE user_id='$userId'";
            $updateProfileResult = mysqli_query($connection, $updateProfileQuery);

            $updateFreelancerQuery = "UPDATE freelancer SET freelancer_identity_photo='$identityFolder', freelancer_verification_photo='$verificationFolder', freelancer_cv='$cvFolder'  WHERE user_id='$userId'";
            $updateFreelancerResult = mysqli_query($connection, $updateFreelancerQuery);
            if ($updateProfileResult && $updateFreelancerResult) {
                header("Location:http://localhost/freelancing-website/dashboard/freelancer/freelancerProfile.php ");
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

<!-- Modal for updating profile -->
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
                <input type="file"  class="inputfile" id="freelancer_identity_photo" name="freelancer_identity_photo" required />
                <label for="freelancer_identity_photo">Identity Photo</label>
                <input  class="inputfile" type="file" id="freelancer_verification_photo" name="freelancer_verification_photo" required />
                <label for="freelancer_verification_photo">Verification Photo</label>
                <input  class="inputfile" type="file" id="freelancer_cv" name="freelancer_cv" required />
                <label for="freelancer_cv">Verification CV</label>
            </div>

            <div class="password-style">
                <div class="password-style-inner">
                    <label class="small-text" for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="userPassword" value="" required><br>
                </div>
                <div class="password-style-inner">
                    <label class="small-text" for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" value="" required><br>
                </div>
                <div class="password-style-inner">
                    <label class="small-text" for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" value="" required><br>
                </div>
            </div>
            <input  class="logout" type="submit" value="Update Profile" name="update">
        </form>
    </div>
</div>
