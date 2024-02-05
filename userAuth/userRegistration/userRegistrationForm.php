<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
</head>
<body>
    <h2>User Registration Form</h2>

    <?php
    // Include the database configuration file
    require("../../config/database/databaseConfig.php");

    // Define variables to hold error messages
    $errors = array();

    try {
        // Check if form is submitted
        if(isset($_POST['Submit'])){
         
            $user_email = $_POST['user_email'];
            $user_phone_number = $_POST['user_phone_number'];
           

            // Check if the email already exists
            $check_email_query = "SELECT * FROM user WHERE user_email='$user_email'";
            $check_email_result = mysqli_query($connection, $check_email_query);
            $check_phone_number_query = "SELECT * FROM user WHERE user_phone_number='$user_phone_number'";
            $check_phone_number_result = mysqli_query($connection, $check_phone_number_query);
            if(mysqli_num_rows($check_email_result) > 0 || mysqli_num_rows($check_phone_number_result) >0) {
                $errors[] = "User with this email or phone number already exists.";
            }
            
            else {

        // Retrieve form data
            $user_first_name = $_POST['user_first_name'];
            $user_last_name = $_POST['user_last_name'];
            $user_password = $_POST['user_password'];
         
            $user_type = $_POST['user_type'];

                 // File upload
            $file_name = $_FILES['user_photo']['name'];
            $temp_name = $_FILES['user_photo']['tmp_name'];
            $folder="pics/".$file_name;
            if (move_uploaded_file($temp_name, $folder)) {
                // echo "File uploaded successfully.";
            } else {
                $errors[] = "Error moving file.";
            }
                // Insert data into database
                $sql = "INSERT INTO user (user_first_name, user_last_name, user_password, user_email, user_phone_number, user_type, user_photo)
                        VALUES ('$user_first_name', '$user_last_name', '$user_password', '$user_email', '$user_phone_number', '$user_type', '$folder')";
                
                $result=mysqli_query($connection,$sql);
                

                if(!$result){
    throw new Exception("Records not inserted");
} else {
$sql1 = "SELECT user_id FROM user WHERE user_email = '$user_email'";
$result1 = mysqli_query($connection, $sql1);

if(mysqli_num_rows($result1) > 0) {
    $row = mysqli_fetch_assoc($result1);
    $user_id = $row['user_id'];
    echo "User ID: " . $user_id;

    // Check user type and redirect accordingly
    if ($user_type === 'Client') {
        header("Location: ClientForm.php?user_id=$user_id");
        exit(); 
    } elseif ($user_type === 'Freelancer') {
        header("Location: freelancerForm.php?user_id=$user_id");
        exit(); 
    } else {
        // Handle unknown user types or future additions
        $errors[] = "Unknown user type.";
    }

    } else {
    echo "No user found with that email.";
}
}

            }
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }

    // Display errors if there are any
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


    <form  method="POST" enctype="multipart/form-data">
        <label for="user_first_name">First Name:</label><br />
        <input type="text" id="user_first_name" name="user_first_name" required /><br /><br />

        <label for="user_last_name">Last Name:</label><br />
        <input type="text" id="user_last_name" name="user_last_name" required /><br /><br />

        <label for="user_password">Password:</label><br />
        <input type="password" id="user_password" name="user_password" required /><br /><br />

        <label for="user_email">Email:</label><br />
        <input type="email" id="user_email" name="user_email" required /><br /><br />

        <label for="user_phone_number">Phone Number:</label><br />
        <input type="tel" id="user_phone_number" name="user_phone_number" pattern="[0-9]*" required /><br /><br />

        <label>User Type:</label><br />
        <input type="radio" id="freelancer" name="user_type" value="Freelancer" required />
        <label for="freelancer">Freelancer</label><br />
        <input type="radio" id="client" name="user_type" value="Client" />
        <label for="client">Client</label><br /><br />

        <label for="user_photo">Photo URL:</label><br />
        <input type="file" id="user_photo" name="user_photo" required /><br /><br />

        <input type="submit" value="Submit" name="Submit" />
    </form>
</body>
</html>
