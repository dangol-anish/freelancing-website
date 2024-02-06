function populateSkills() {
  var category = document.getElementById("freelancer_category").value;
  var skillsSelect = document.getElementById("freelancer_skills");
  skillsSelect.innerHTML = "";

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "fetchSkills.php?category=" + encodeURIComponent(category),
    true
  );
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var skills = JSON.parse(xhr.responseText);

      skills.forEach(function (skill) {
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
