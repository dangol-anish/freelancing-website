<?php
session_start();

include("../config/database/databaseConfig.php");
$job_id = $_GET['job_id'];
$usersQuery = "SELECT * FROM user WHERE user_id = '" . $_SESSION["user_id"] . "'";
$getUsersResult = mysqli_query($connection, $usersQuery);
$user = mysqli_fetch_assoc($getUsersResult);
if(!isset($_SESSION["user_id"]) && !isset($_SESSION["login"])) {
   
        $_SESSION = [];
        session_destroy();
        header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");
        exit;
 
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My chatbox</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container-fluid">
    <div >
        <div >
   
            <input type="text" id="fromUser" value="<?php echo $user["user_id"]; ?>" hidden/>
  
           
        </div>
        <div >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>
                            <?php
                            if (isset($_GET['toUser'])) {
                                $userName = "SELECT *    FROM user WHERE user_id = '" . $_GET["toUser"] . "'";
                                $userNameResult = mysqli_query($connection, $userName);
                                $uName = mysqli_fetch_assoc($userNameResult);
                                echo '<input type="text" value="' . $_GET["toUser"] . '" id="toUser" hidden/>';
                                echo $uName["user_first_name"] . " " .$uName["user_last_name"];
                            } else {
                                echo '<input type="text" value="' . $_SESSION["user_id"] . '" id="toUser" hidden/>';
                                echo $user["user_first_name"];
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="modal-body" id="msgBody">
                        <?php
                        if (isset($_GET["toUser"])) {
                            $chats = mysqli_query($connection, "SELECT * FROM messages WHERE ((fromUser = '" . $_SESSION["user_id"] . "' AND
                                toUser = '" . $_GET["toUser"] . "') OR (fromUser = '" . $_GET["toUser"] . "' AND
                                toUser = '" . $_SESSION["user_id"] . "')) ");


                            while ($chat = mysqli_fetch_assoc($chats)) {
                                if ($chat["fromUser"] == $_SESSION["user_id"]) {
                                    echo "<div class='sender'>" . $chat["Message"] . "</div>";
                                } else {
                                    echo "<div class='receiver'>" . $chat["Message"] . "</div>";
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="message-send">
                        <textarea placeholder="Enter a message..." name="" id="message"  cols="30" rows="10"></textarea>
                        <button id="send" class="btn">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div >
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $("#send").on("click", function () {
        var message = $("#message").val().trim(); // Trim whitespace from the message

        // Check if the message is not empty
        if (message !== "") {
            $.ajax({
                url: "insertMessage.php?job_id=<?php echo $job_id?>",
                method: "POST",
                data: {
                    fromUser: $("#fromUser").val(),
                    toUser: $("#toUser").val(),
                    message: message, // Use the trimmed message
                },
                dataType: "text",
                success: function (data) {
                    $("#message").val("");
                }
            });
        } else {
            // Inform the user that the message cannot be empty
            alert("Message cannot be empty!");
        }
    });

    setInterval(function () {
        $.ajax({
            url: "realTimeChat.php?job_id=<?php echo $job_id?>",
            method: "POST",
            data: {
                fromUser: $("#fromUser").val(),
                toUser: $("#toUser").val(),
            },
            dataType: "text",
            success: function (data) {
                $("#msgBody").html(data);
            }
        });
    }, 700);
});
</script>
</html>
