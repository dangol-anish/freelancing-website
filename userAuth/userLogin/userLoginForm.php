<?php

session_start(); // Start the session

// Check if the user is already logged in
if(isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/dashboard/dashboard.php");
    exit; // Make sure no code is executed after redirection
}

?>

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
            $userId = $row["user_id"];

            if (password_verify($userPassword, $storedUserPassword)) {
                session_start();
                $_SESSION["user_id"] = $userId;
                $_SESSION["login"] = true;
                header("Location: http://localhost/freelancing-website/dashboard/dashboard.php");

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
