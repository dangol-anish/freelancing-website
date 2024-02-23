<?php

require("../../config/database/databaseConfig.php");


if (isset($_GET['category'])) {

    $category = mysqli_real_escape_string($connection, $_GET['category']);


    $query = "SELECT * FROM Skill WHERE skill_category = '$category' and skill_approval = 1";
    $result = mysqli_query($connection, $query);


    $skills = array();


    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $skills[] = array(
                'skill_id' => $row['skill_id'],
                'skill_name' => $row['skill_name']
            );
        }
    }


    echo json_encode($skills);
} else {

    echo json_encode(array());
}

