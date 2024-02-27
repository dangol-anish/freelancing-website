<?php
include("../../config/database/databaseConfig.php");

$errors = array();

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

if ($_SESSION["user_type"] != "Client") {
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
    exit();
}

$userId = $_SESSION["user_id"];
?>

<?php
if (isset($_POST["submit"])) {
    try {
        if (!empty($userId)) {
            $jobTitle = mysqli_real_escape_string($connection, $_POST["job_title"]);
            $jobDescription = mysqli_real_escape_string($connection, $_POST["job_description"]);
            $jobBudget = $_POST["job_budget"];
            $jobDurationNumber = $_POST["job_duration_number"];
            $jobDurationUnit = $_POST["job_duration_unit"];
            $jobDuration = $jobDurationNumber . ' ' . $jobDurationUnit;

            // Insert job data
            if (empty($errors)) {
                $insertJobDataQuery = "INSERT INTO job (job_title, job_description, job_budget, job_duration, job_status, user_id) VALUES ('$jobTitle', '$jobDescription', '$jobBudget', '$jobDuration', 0, '$userId')";
                $insertJobDataResult = mysqli_query($connection, $insertJobDataQuery);

                if (!$insertJobDataResult) {
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

<div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="create-job-heading">Create a New Job</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" onsubmit="return validateForm()">
            <div class="section-two">
                <div class="text-info">
                    <label for="job_title">
                        <p>Job Title</p><input type="text" name="job_title" id="job_title" required>
                    </label>
                    <label for="job_description">
                        <p>Job Description</p>
                        <textarea name="job_description" id="job_description" rows="17" cols="50" style="resize: none;" required></textarea>
                    </label>
                </div>
                <div class="budget-duration-info">
                    <label for="job_budget">
                        <p>Budget</p>
                        <input type="number" name="job_budget" id="job_budget" min="1" required>
                    </label>
                    <label for="job_duration">
                        <p>Duration</p>
                        <input type="number" name="job_duration_number" id="job_duration_number" min="1" required>
                        <select class="job-duration" name="job_duration_unit" id="job_duration_unit" required>
                            <option value="days">Days</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                    </label>
                    <label for="job_category">
                        <p>Job Category</p>
                        <select id="job_category" class="job-cat" name="job_category" onchange="populateSkills()" required>
                            <option value="">Select Category</option>
                            <?php
                            $query = "SELECT DISTINCT skill_category FROM skill";
                            $result = mysqli_query($connection, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['skill_category'] . "'>" . $row['skill_category'] . "</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <br>
                    <label class="job-skills" for="job_skills">Required Skills</label>
                    <div id="job_skills" class="scrollable-skills" required></div>
                    <p class="new-skills">Looking for different skills? <a href="http://localhost/freelancing-website/config/helper/addSkills.php">Add new ones</a></p>
                </div>
            </div>
            <input class="createBtn" type="submit" value="Create Job" name="submit">
        </form>
    </div>
</div>

<script src="createJob.js"></script>

<script>
    function validateForm() {
        var selectedSkills = document.querySelectorAll('#job_skills input[type="checkbox"]:checked');

        if (selectedSkills.length === 0) {
            alert("Please select at least one skill.");
            return false; // Prevent form submission
        }

        // If skills are selected, allow form submission
        return true;
    }
</script>
