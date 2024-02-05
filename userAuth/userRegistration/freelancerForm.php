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
            require("../../config/database/databaseConfig.php");

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

    <script>
        function populateSkills() {
            var category = document.getElementById("freelancer_category").value;
            var skillsSelect = document.getElementById("freelancer_skills");
            skillsSelect.innerHTML = ""; 


            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetchSkills.php?category=" + encodeURIComponent(category), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var skills = JSON.parse(xhr.responseText);
   

                    skills.forEach(function(skill) {
                        var checkbox = document.createElement("input");
                        checkbox.type = "checkbox";
                        checkbox.name = "freelancer_skills[]";
                        checkbox.value = skill.skill_id;
                        checkbox.id = "skill_" + skill.skill_id;

                        var label = document.createElement("label");
                        label.htmlFor = "skill_" + skill.skill_id;
                        label.appendChild(document.createTextNode(skill.skill_name));

                        var br = document.createElement("br");

                        skillsSelect.appendChild(checkbox);
                        skillsSelect.appendChild(label);
                        skillsSelect.appendChild(br);
                    });
                }
            };
            xhr.send();
        }
    </script>

</body>
</html>

