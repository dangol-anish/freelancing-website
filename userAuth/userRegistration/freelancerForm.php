<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Form</title>
</head>
<body>
    <h2>Welcome Freelancer!</h2>

    <?php
    // Check if the userID is passed in the URL
    if(isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        echo "<p>Your User ID is: $user_id</p>";
    } else {
        echo "<p>User ID not found.</p>";
    }
    ?>

    <!-- Your freelancer form goes here -->
</body>
</html>
