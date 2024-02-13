<?php
session_start(); // Start the session if not already started

include("../../config/database/databaseConfig.php");



if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Client"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

    }

    $userId = $_SESSION ["user_id"];

    $getClientStatusQuery = "select user_status from user where user_id='$userId'";

    $getClientStatusResult = mysqli_query($connection, $getClientStatusQuery);

    if(mysqli_num_rows($getClientStatusResult) >=0) {
        $row = mysqli_fetch_assoc($getClientStatusResult);

        $userStatus = $row['user_status'];
          $disableCreateJobButton = ($userStatus == 2 || $userStatus == 0);

    }


    
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="clientDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<nav> 
        <h2>LOGO</h2>
    <div>
      <button id="createJob" class="createJobModal" <?php if(isset($disableCreateJobButton) && $disableCreateJobButton) echo 'disabled'; ?>> Create Job</button>

    <a href="http://localhost/freelancing-website/dashboard/client/clientProfile.php">My Profile</a>
    <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
    </div>
</nav>


<?php include("createJob.php");?>

<script>
  
    var modalBtn = document.getElementById("createJob");


    var modal = document.getElementById("updateProfileModal");


    var span = document.getElementsByClassName("close")[0];

    modalBtn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

  
</body>
</html>

