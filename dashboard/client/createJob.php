<?php
  
    // Include database configuration file only once
    include_once("../../config/database/databaseConfig.php");

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

        <form action="submit_job.php" method="POST">
            <label for="job-title">Add a job title:</label>
            <input type="text" name="job-title" id="job-title" required>
            <br><br>
            <label for="job-description">Add a job description:</label>
            <input type="text" name="job-description" id="job-description" required>
            <br><br>
            <label for="job-budget">Add an estimated budget (Nepalese Rupee):</label>
            <input type="number" name="job-budget" id="job-budget" required>
            <br><br>
            <label for="job-duration">Estimated duration:</label>
            <input type="number" name="job-duration-number" id="job-duration-number" required>
            <select name="job-duration-unit" id="job-duration-unit" required>
                <option value="days">Days</option>
                <option value="months">Months</option>
                <option value="years">Years</option>
            </select>
            <br><br>
          
        <label for="job_category">Job Category:</label><br />
        <select id="job_category" name="job_category" onchange="populateSkills()">
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
        <div id="job_skills"></div>
        <br /><br />
            <input type="submit" value="Create Job">
        </form>
    </div>
</div>

  <script src="createJob.js"></script>