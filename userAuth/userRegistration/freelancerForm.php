<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Form</title>
</head>
<body>
    <h2>Freelancer Verification Form!</h2>

    <?php

     // Include the database configuration file
    require("../../config/database/databaseConfig.php");

    if(isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        echo ($user_id);
    } 

    






    ?>

 <form method="POST" enctype="multipart/form-data">

        <label for="freelancer_verification_photo">Verification Photo:</label><br />
        <input type="file" id="freelancer_verification_photo" name="freelancer_verification_photo" required /><br /><br />


        <label for="freelancer_bio">Write your bio:</label>
<br>
<textarea id="freelancer_bio" name="freelancer_bio" rows="4" cols="50">

</textarea>


<br>


 <label for="freelancer_cv">Verification CV:</label><br />
        <input type="file" id="freelancer_cv" name="freelancer_cv" required /><br /><br />


        <input type="submit" value="Submit" name="Submit" />
    </form>
    <!-- Your freelancer form goes here -->
</body>
</html>
