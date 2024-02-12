<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

include("../../config/database/databaseConfig.php");

session_start(); 

if (isset($_GET["user_verification_id"]) && isset($_SESSION["login"]) && isset($_GET["user_type"])) {

    $userId = $_GET["user_verification_id"];
    $userType = $_GET["user_type"];

    $getUsersQuery = "SELECT * FROM user WHERE user_id = '$userId'";
    
    $getUsersResult = mysqli_query($connection, $getUsersQuery);

    if (mysqli_num_rows($getUsersResult) > 0) {
        while ($row = mysqli_fetch_assoc($getUsersResult)) {
            $userFirstName = $row['user_first_name'];
            $userLastName = $row['user_last_name'];
            $userEmail = $row['user_email'];
            $userPhoneNumber = $row['user_phone_number'];
            $userType = $row['user_type'];
            $userPhoto = $row['user_photo'];
        }
    }

    if ($userType == "Client") {

        $getClientsQuery = "SELECT * FROM client WHERE user_id = '$userId'";
        $getClientsResult = mysqli_query($connection, $getClientsQuery);

        if (mysqli_num_rows($getClientsResult) > 0) {
            while ($row = mysqli_fetch_assoc($getClientsResult)) {
                $clientPanPhoto = $row["client_pan_photo"];
                $clientVerificationPhoto = $row["client_verification_photo"];
                $clientBio = $row["client_bio"];
                $clientCv = $row["client_cv"];
            }
        }
    } elseif ($userType == "Freelancer") {

        $getFreelancersQuery = "SELECT * FROM freelancer WHERE user_id = '$userId'";
        $getFreelancersResult = mysqli_query($connection, $getFreelancersQuery);

        if (mysqli_num_rows($getFreelancersResult) > 0) {
            while ($row = mysqli_fetch_assoc($getFreelancersResult)) {
                $freelancerIdentityPhoto = $row["freelancer_identity_photo"];
                $freelancerVerificationPhoto = $row["freelancer_verification_photo"];
                $freelancerBio = $row["freelancer_bio"];
                $freelancerCV = $row["freelancer_cv"];
            }
        }
    } else {
        echo "Invalid User Type";
    }
} else {
    echo "Error";
}
?>

</body>
</html>
