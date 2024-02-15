<?php
  
    // Include database configuration file only once
    include("../../config/database/databaseConfig.php");

    $errors = array(); // Initialize errors array for error handling

    // Check if user is logged in
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit(); 
    }

    // Check if the logged-in user is a client
    if ($_SESSION["user_type"] != "Client") {
        // If not a client, destroy session and redirect to login page
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit();
    }

    $userId = $_SESSION["user_id"];
    
?>

<!-- Modal for updating profile -->
<div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Create a New Job</h2>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="job_title">Add a job title:</label>
            <input type="text" name="job_title" id="job_title" required>
            <br><br>
            <label for="job_description">Add a job description:</label>
            <input type="text" name="job_description" id="job_description" required>
            <br><br>
            <label for="job_budget">Add an estimated budget (Nepalese Rupee):</label>
            <input type="number" name="job_budget" id="job_budget" required>
            <br><br>
            <label for="job_duration">Estimated duration:</label>
            <input type="number" name="job_duration_number" id="job_duration_number" required>
            <select name="job_duration_unit" id="job_duration_unit" required>
                <option value="days">Days</option>
                <option value="months">Months</option>
                <option value="years">Years</option>
            </select>
            <br><br>
          
        <label for="job_category">Job Category:</label><br />
        <select id="job_category" name="job_category" onchange="populateSkills()" required>
            <option value="">Select Category</option>

            <?php
            require("../../config/database/databaseConfig.php");

            $query = "SELECT DISTINCT skill_category FROM skill";
            $result = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['skill_category'] . "'>" . $row['skill_category'] . "</option>";
            }
            ?>
        </select><br /><br />

        <label for="job_skills">Required Skills:</label><br />
        <div id="job_skills" required></div>
        <br /><br /> 
            <input type="submit" value="Create Job" name="submit">
        </form>
    </div>
</div>

  <script src="createJob.js"></script>


<?php
    // Include database configuration file only once
    include("../../config/database/databaseConfig.php");

    $errors = array(); // Initialize errors array for error handling

    // Check if user is logged in
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit(); 
    }

    // Check if the logged-in user is a client
    if ($_SESSION["user_type"] != "Client") {
        // If not a client, destroy session and redirect to login page
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit();
    }

    $userId = $_SESSION["user_id"];
    

    if(isset($_POST["submit"])){
        try {
            if(!empty($userId)) {
                $jobTitle = $_POST["job_title"];
                $jobDescription = $_POST["job_description"];
                $jobBudget = $_POST["job_budget"];
                $jobDurationNumber = $_POST["job_duration_number"];
                $jobDurationUnit = $_POST["job_duration_unit"];
                $jobDuration = $jobDurationNumber . ' ' . $jobDurationUnit;

                // insert job data
                if(empty($errors)) {
                    $insertJobDataQuery = "INSERT INTO job (job_title, job_description, job_budget, job_duration, job_status, user_id) VALUES ('$jobTitle', '$jobDescription', '$jobBudget', '$jobDuration', 1, '$userId')";
                    $insertJobDataResult = mysqli_query($connection, $insertJobDataQuery);

                    if(!$insertJobDataResult) {
                        throw new Exception("Records not inserted");
                    } else {
                        // Get the newly inserted job id
                        $jobId = mysqli_insert_id($connection);
                        // Insert job skills
                        $selectedSkills = $_POST['job_skills'];
                        foreach ($selectedSkills as $skill) {
                            $insertSkillQuery = "INSERT INTO job_skill (job_id, skill_id) VALUES ('$jobId', '$skill')";
                            $freelancerSkill = mysqli_query($connection, $insertSkillQuery);
                        }
                        // Redirect to another page to avoid form resubmission
                        header("Location: http://localhost/freelancing-website/dashboard/client/clientDashboard.php");
                        exit();
                    }
                }
            }
        } catch (Exception $e) {
            // Handle the exception here, if needed
            $errors[] = $e->getMessage();
        }
    }
?>