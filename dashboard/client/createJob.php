
    <link rel="stylesheet" href="client.css">

    <?php
    include("../../config/database/databaseConfig.php");

$errors = array();



    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["login"])) {
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit(); 
    }

    if ($_SESSION["user_type"] != "Client") {
        $_SESSION = [];
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
            <h2>Update Profile</h2>
          <p>Hello</p>

        </div>
    </div>
