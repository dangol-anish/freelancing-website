<?php
session_start(); // Start the session if not already started

include("../../config/database/databaseConfig.php");

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit(); 
}

if($_SESSION["user_type"] != "Freelancer"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

$userId = $_SESSION["user_id"];
$loginStatus = $_SESSION["login"];
$userType = $_SESSION["user_type"];

$getuserInfoQuery = "SELECT * FROM user WHERE user_id='$userId'";
$getuserInfoResult = mysqli_query($connection, $getuserInfoQuery);

$getFreelancerInfoQuery = "SELECT * FROM freelancer WHERE user_id='$userId'";
$getFreelancerInfoResult = mysqli_query($connection, $getFreelancerInfoQuery);

if (mysqli_num_rows($getuserInfoResult) <= 0 || mysqli_num_rows($getFreelancerInfoResult) <= 0) {
    echo "No user Found";
} else {
    $row = mysqli_fetch_assoc($getuserInfoResult);
    $userFirstName = $row["user_first_name"];
    $userLastName = $row["user_last_name"];
    $userEmail = $row["user_email"];
    $userPhoneNumber = $row["user_phone_number"];
    $userPhoto = $row["user_photo"];
    $userPassword = $row["user_password"];
    $userStatusNumber = $row["user_status"];
    $userStatus = "";

    if($userStatusNumber == 0){
        $userStatus = "In review";
    }else if ($userStatusNumber == 1){
        $userStatus = "Verified";
    }else if ($userStatusNumber == 2){
        $userStatus = "Rejected";
    }

    $freelancerRow = mysqli_fetch_assoc($getFreelancerInfoResult);
    $freelancerIdentityPhoto = $freelancerRow["freelancer_identity_photo"];
    $freelancerVerificationPhoto = $freelancerRow["freelancer_verification_photo"];
    $freelancerBio = $freelancerRow["freelancer_bio"];
    $freelancerCv = $freelancerRow["freelancer_cv"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Add your CSS links here -->
</head>
<body>

<main>
    <div class="card">
        <div class="user-photo">
            <img src="../../userAuth/userRegistration/<?php echo $userPhoto; ?>" alt="User Photo">
        </div>
        <div class="user_info">
            <h2>Freelancer Information</h2>
            <p><strong>User Status: <?php echo $userStatus?></strong></p>
            <p><strong>Name:</strong> <?php echo $userFirstName . ' ' . $userLastName; ?></p>
            <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $userPhoneNumber; ?></p>
            <p><strong>User Type:</strong> <?php echo $userType; ?></p>
            <p><strong>Freelancer Bio:</strong> <?php echo $freelancerBio; ?></p>
            <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerIdentityPhoto; ?>" target="_blank">View Freelancer Identity Photo</a></p>
            <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerVerificationPhoto; ?>" target="_blank">View Freelancer Verification Photo</a></p>
            <p><a href="../../userAuth/userRegistration/freelancerRegistration/<?php echo $freelancerCv; ?>" target="_blank">View CV</a></p>

            <!-- Button to open update profile modal -->
            <button id="openModalBtn">Edit your profile</button>
        </div>
    </div>
</main>

<!-- Include update profile modal -->
<?php include("updateProfile.php"); ?>

<!-- JavaScript to handle modal behavior -->
<script>
    // Get the button that opens the modal
    var modalBtn = document.getElementById("openModalBtn");

    // Get the modal
    var modal = document.getElementById("updateProfileModal");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    modalBtn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
