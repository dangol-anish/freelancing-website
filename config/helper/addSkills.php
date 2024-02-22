<?php

// Start session and include database configuration
session_start();
include("../../config/database/databaseConfig.php");

$userType = $_SESSION["user_type"];

// Check if user is logged in
if (isset($_SESSION["user_id"]) || isset($_SESSION["login"])) {
    if (!$userType) {
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit();
    }
} else {
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="addSkills.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<nav>
    <?php
    // Display navigation based on user type
    if ($userType == "Admin") {
        echo "<span class='material-symbols-outlined'>arrow_back_ios</span><a href='http://localhost/freelancing-website/dashboard/admin/adminDashboard.php'>Go Back</a>";
    } elseif ($userType == "Client") {
        echo "<span class='material-symbols-outlined'>arrow_back_ios</span><a href='http://localhost/freelancing-website/dashboard/client/clientDashboard.php'>Go Back</a>";
    } elseif ($userType == "Freelancer") {
        echo "<span class='material-symbols-outlined'>arrow_back_ios</span><a href='http://localhost/freelancing-website/dashboard/freelancer/freelancerDashboard.php'>Go Back</a>";
    }
    ?>
</nav>
<div class="mid">
    <form action="" method="post">
        <div class="skill-new">
            <select id="freelancer_category" name="freelancer_category" onchange="populateSkills()" required>
                <option value="">Select Category</option>
                <?php
                // Fetch distinct skill categories
                $query = "SELECT DISTINCT skill_category FROM skill";
                $result = mysqli_query($connection, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['skill_category'] . "'>" . $row['skill_category'] . "</option>";
                }
                ?>
            </select>
            <input placeholder="Skill Name..." type="text" name="skill_name" id="">
            <input type="submit" value="Add" name="submit">
        </div>
        <?php
        // Handle form submission for adding skills
        if (isset($_POST['submit'])) {
            $category = $_POST['freelancer_category'];
            $skillName = $_POST['skill_name'];
            $checkQuery = "SELECT * FROM skill WHERE skill_name = '$skillName'";
            $checkResult = mysqli_query($connection, $checkQuery);
            if (mysqli_num_rows($checkResult) > 0) {
                echo "<p style='text-align:center;'>Skill already exists!</p>";
            } else {
                $query = "INSERT INTO skill (skill_category, skill_name, skill_approval) VALUES ('$category', '$skillName', 0)";
                $result = mysqli_query($connection, $query);
                if ($result) {
                    if ($userType == "Freelancer" || $userType == "Client") {
                        echo "<p style='text-align:center; padding-top:20px;'>Skill added successfully! It will appear once verified.</p>";
                    }
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
            }
        }
        ?>
    </form>
    <?php
    // Display unverified skills table for Admin
    if ($userType == "Admin") {
        echo "<table>
            <thead>
            <tr>
                <th>Skill Category</th>
                <th>Skill Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>";
        // Query unverified skills
        $query = "SELECT skill_category, skill_name, skill_id FROM skill WHERE skill_approval = 0";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['skill_category'] . "</td>";
            echo "<td>" . $row['skill_name'] . "</td>";
            // Form for deleting skill
            echo "<td class='action-btn'>
                    <form class='addskills' action='' method='post'>
                        <input type='hidden' name='verify_skill' value='" . $row['skill_id'] . "'>
                        <input type='submit' value='Verify'>
                    </form>
                    <form class='addskills' action='' method='post'>
                        <input  class='reject'  type='hidden' name='delete_skill' value='" . $row['skill_id'] . "'>
                        <input class='reject' type='submit' value='Delete'>
                    </form>
                </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    ?>
</div>
</body>
</html>

<?php
// Handle skill verification
if (isset($_POST['verify_skill'])) {
    $skillId = $_POST['verify_skill'];
    $query = "UPDATE skill SET skill_approval = 1 WHERE skill_id = $skillId";
    $result = mysqli_query($connection, $query);
    if ($result) {
        header("Location: http://localhost/freelancing-website/config/helper/addSkills.php");
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Handle skill deletion
if (isset($_POST['delete_skill'])) {
    $skillId = $_POST['delete_skill'];
    $query = "DELETE FROM skill WHERE skill_id = $skillId";
    $result = mysqli_query($connection, $query);
    if ($result) {
        // Redirect to the same page after deletion
        header("Location: http://localhost/freelancing-website/config/helper/addSkills.php");
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
