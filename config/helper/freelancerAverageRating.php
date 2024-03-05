
<?php

include("../../config/database/databaseConfig.php");

$userId = $_SESSION["user_id"];
$userType = $_SESSION["user_type"];


// Check if user is logged in
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

// Fetch all ratings for the user
$query = "SELECT rating FROM freelancer_rating WHERE freelancer_user_id = $userId";

$ratingsResult = mysqli_query($connection, $query);

// Check if there are any ratings
if (mysqli_num_rows($ratingsResult) > 0) {
    $totalRating = 0;
    $numRatings = 0;

    // Sum up ratings and count the number of ratings
    while ($row = mysqli_fetch_assoc($ratingsResult)) {
        $totalRating += $row['rating'];
        $numRatings++;
    }

    // Calculate average rating
    $averageRating = $numRatings > 0 ? $totalRating / $numRatings : 0;

    // Display average rating
    echo number_format($averageRating, 2);

    $insertAverageRating = "update freelancer set average_rating = '$averageRating' where user_id='$userId'";
    $insertRatingQuery = mysqli_query($connection, $insertAverageRating);

} else {
    // No ratings found
    echo "No ratings yet.";
}

?>
