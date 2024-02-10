<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./adminDashboard.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<nav>
    <ul>
        <li><a href="#">Client</a></li>
        <li><a href="#">Freelancer</a></li>
    </ul>
    <a href="http://localhost/freelancing-website/dashboard/logout.php">Logout</a>
</nav>

<main>
    <section>
        <table border="1px">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("../../config/database/databaseConfig.php");

                $getUsersQuery = "SELECT user_id, user_first_name, user_last_name, user_email, user_phone_number, user_type, user_photo FROM user";

                $getUsersResult = mysqli_query($connection, $getUsersQuery);

                if(mysqli_num_rows($getUsersResult) > 0) {
                    while($row = mysqli_fetch_assoc($getUsersResult)) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id']. "</td>";
                        echo "<td>" . $row['user_first_name'] . " " . $row['user_last_name'] . "</td>";
                        echo "<td>" . $row['user_email'] . "</td>";
                        echo "<td>" . $row['user_phone_number'] . "</td>";
                        echo "<td>" . $row['user_type'] . "</td>";
                        echo "<td><a href='userVerify.php?user_id=" . $row['user_id'] . "'>Verify User</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found.</td></tr>";
                }
                mysqli_close($connection);
                ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
