<?php

 require("../../../config/helper/persistLogin.php");
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Form</title>
</head>
<body>
    <h2>Freelancer Verification Form!</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="freelancer_identity_photo">Identity Photo:</label><br />
        <input type="file" id="freelancer_identity_photo" name="freelancer_identity_photo" required /><br /><br />

        <label for="freelancer_verification_photo">Verification Photo:</label><br />
        <input type="file" id="freelancer_verification_photo" name="freelancer_verification_photo" required /><br /><br />

        <label for="freelancer_bio">Write your bio:</label><br />
        <textarea id="freelancer_bio" name="freelancer_bio" rows="4" cols="50"></textarea><br /><br />

        <label for="freelancer_cv">Verification CV:</label><br />
        <input type="file" id="freelancer_cv" name="freelancer_cv" required /><br /><br />

        <label for="freelancer_category">Select Category:</label><br />
        <select id="freelancer_category" name="freelancer_category" onchange="populateSkills()">
            <option value="">Select Category</option>

            <?php
            require("../../../config/database/databaseConfig.php");

            $query = "SELECT DISTINCT skill_category FROM skill";
            $result = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['skill_category'] . "'>" . $row['skill_category'] . "</option>";
            }
            ?>
        </select><br /><br />

        <label for="freelancer_skills">Select Skills:</label><br />
        <div id="freelancer_skills"></div>
        <br /><br />
        <input type="submit" value="Submit" name="Submit" />
    </form>

    <script src="freelancerForm.js"></script>
</body>
</html>

<?php
require("../../../config/database/databaseConfig.php");

$errors = array();
$userId = ""; 

try {
    if(isset($_GET['user_id'])) {
        $userId = $_GET['user_id']; 
    } else {
        $errors[] = "No User ID";
    }

    if(isset($_POST['Submit'])) {
        if(!empty($userId)) {
            if(isset($_FILES['freelancer_verification_photo']) && isset($_FILES['freelancer_cv'])) {

                $getUserIdQuery = "SELECT * FROM user WHERE user_id='$userId'";
                $getUserIdResult = mysqli_query($connection, $getUserIdQuery);

                if(mysqli_num_rows($getUserIdResult) == 0) {
                    $errors[] = "You are not an existing user";
                } else { 
                    if(isset($_FILES['freelancer_identity_photo'])) {
                        $freelancerIdentityPhoto = $_FILES['freelancer_identity_photo']['name'];
                        $photoTempName = $_FILES['freelancer_identity_photo']['tmp_name'];
                        $identityFolder = "identity/".$freelancerIdentityPhoto;

                        if (move_uploaded_file($photoTempName, $identityFolder)) {
                            echo "Identity Photo uploaded successfully.";
                        } else {
                            $errors[] = "Error moving identity photo file.";
                        }
                    } else {
                        $errors[] = "Identity photo file not provided.";
                    }

                    // Check if 'freelancer_verification_photo' index is set
                    if(isset($_FILES['freelancer_verification_photo'])) {
                        $freelancerVerificationPhoto = $_FILES['freelancer_verification_photo']['name'];
                        $photoTempName = $_FILES['freelancer_verification_photo']['tmp_name'];
                        $verificationFolder = "verification/".$freelancerVerificationPhoto;

                        if (move_uploaded_file($photoTempName, $verificationFolder)) {
                            echo "Verification Photo uploaded successfully.";
                        } else {
                            $errors[] = "Error moving verification photo file.";
                        }
                    } else {
                        $errors[] = "Verification photo file not provided.";
                    }

                    $freelancerBio = $_POST['freelancer_bio'];

                    // Check if 'freelancer_cv' index is set
                    if(isset($_FILES['freelancer_cv'])) {
                        $freelancerCv = $_FILES['freelancer_cv']['name'];
                        $freelancerTempName = $_FILES['freelancer_cv']['tmp_name'];
                        $cvFolder = "cv/".$freelancerCv;

                        if (move_uploaded_file($freelancerTempName, $cvFolder)) {
                            echo "CV uploaded successfully.";
                        } else {
                            $errors[] = "Error moving CV file.";
                        }
                    } else {
                        $errors[] = "CV file not provided.";
                    }

                    // Insert freelancer data only if no errors occurred during file uploads
                    if(empty($errors)) {
                        $checkFreelancerDataQuery = "SELECT * FROM freelancer WHERE user_id = '$userId'";
                        $checkFreelancerDataResult = mysqli_query($connection, $checkFreelancerDataQuery);

                        if(mysqli_num_rows($checkFreelancerDataResult) > 0) {
                            echo "Data already exists";
                        } else {
                            $insertFreelancerData = "INSERT INTO freelancer (freelancer_identity_photo, freelancer_verification_photo, freelancer_bio, freelancer_cv, user_id)
                                VALUES ('$identityFolder','$verificationFolder', '$freelancerBio',  '$freelancerCv', '$userId')";

                            $freelancerData = mysqli_query($connection, $insertFreelancerData);

                            if(!$freelancerData) {
                                throw new Exception("Records not inserted");
                            } 

                            $selectedSkills = $_POST['freelancer_skills'];
                            foreach ($selectedSkills as $skill) {
                                $insertSkillQuery = "INSERT INTO freelancer_skill (user_id, skill_id) VALUES ('$userId', '$skill')";
                                $freelancerSkill = mysqli_query($connection, $insertSkillQuery);
                            }
                        }
                    }
                }
            } else {
                $errors[] = "Verification photo or CV file not provided.";
            }
        }
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}
?>
