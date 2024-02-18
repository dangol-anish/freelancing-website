<?php
session_start();

include("../config/database/databaseConfig.php");
include("links.php");

$usersQuery = "SELECT * FROM user WHERE user_id = '" . $_SESSION["user_id"] . "'";
$getUsersResult = mysqli_query($connection, $usersQuery);
$user = mysqli_fetch_assoc($getUsersResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My chatbox</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <p>Hi <?php echo $user["user_first_name"]; ?></p>
            <input type="text" id="fromUser" value="<?php echo $user["user_id"]; ?>" hidden/>
  
            <ul>
                <?php
             
                ?>
            </ul>
        </div>
        <div class="col-md-4">
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
                                echo $uName["user_first_name"] . $uName["user_last_name"];
                            } else {
                                echo '<input type="text" value="' . $_SESSION["user_id"] . '" id="toUser" hidden/>';
                                echo $user["user_first_name"];
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="modal-body" id="msgBody" style="height: 400px; overflow-y: scroll; overflow-x: hidden;">
                        <?php
                        if (isset($_GET["toUser"])) {
                            $chats = mysqli_query($connection, "SELECT * FROM messages WHERE (fromUser = '" . $_SESSION["user_id"] . "' AND
                                toUser = '" . $_GET["toUser"] . "') OR (fromUser = '" . $_GET["toUser"] . "' AND
                                toUser = '" . $_SESSION["user_id"] . "')");

                            while ($chat = mysqli_fetch_assoc($chats)) {
                                if ($chat["fromUser"] == $_SESSION["user_id"]) {
                                    echo "<div style='text-align: right;'>
                                            <p style='background-color: lightblue; word-wrap: break-word; display: inline-block; padding: 5px; border-radius: 10px; max-width: 70%;'>" . $chat["Message"] . "</p>
                                          </div>";
                                } else {
                                    echo "<div style='text-align: left;'>
                                            <p style='background-color: yellow; word-wrap: break-word; display: inline-block; padding: 5px; border-radius: 10px; max-width: 70%;'>" . $chat["Message"] . "</p>
                                          </div>";
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <textarea name="" id="message" class="form-control" style="height: 70px;" cols="30"
                                  rows="10"></textarea>
                        <button id="send" class="btn btn-primary" style="height: 70%;">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
 $(document).ready(function () {
    $("#send").on("click", function () {
        var message = $("#message").val().trim(); // Trim whitespace from the message

        // Check if the message is not empty
        if (message !== "") {
            $.ajax({
                url: "insertMessage.php",
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
            url: "realTimeChat.php",
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
