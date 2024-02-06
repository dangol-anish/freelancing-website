<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>User Login Form</h2>

    <form action="" method="POST">
        <label for="user_email">Email:</label><br />
        <input type="text" id="user_email" name="user_email" required /><br /><br />

        <label for="user_password">Password:</label><br />
        <input type="password" id="user_password" name="user_password" required /><br /><br />

        <input type="submit" value="Submit" name="Submit">
    </form>
    
</body>
</html>

<?php

require("../../config/database/databaseConfig.php");

$errors = array();

try {
    if(isset($_POST["Submit"])) {
        $userEmail = $_POST["user_email"];
        $userPassword = $_POST["user_password"];

        $checkUserQuery = "select user_id, user_password from user where user_email= '$userEmail'";
        $checkUserResult = mysqli_query($connection, $checkUserQuery);

        if(mysqli_num_rows($checkUserResult) == 0) {
            $errors[] = "Your user email or password doesn't match";
        } else {
            $row = mysqli_fetch_assoc($checkUserResult);
            $storedUserPassword = $row["user_password"];

            if (password_verify($userPassword, $storedUserPassword)) {
                echo "Login successful. User ID: " . $row["user_id"];
            } else {
                $errors[] = "Your user email or password doesn't match";
            }
        }
    }
} catch(Exception $e) {
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