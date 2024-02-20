 <?php
session_start(); // Start the session if not already started

if( isset($_SESSION["user_id"]) && isset($_SESSION["login"])) {

    if($_SESSION["user_type"] != "Admin"){
    $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

    }
    
}else{
     $_SESSION = [];
    session_destroy();
    header("Location: http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php");

}

include("../../config/database/databaseConfig.php");

// Retrieve users from the database
$getUsersQuery = "SELECT user_id, user_first_name, user_last_name, user_email, user_phone_number, user_type, user_photo FROM user WHERE user_type <>
'admin' AND user_status = 0"; // Check if user type filter is set
if(isset($_GET['user_type'])) {
   $userType = $_GET['user_type']; 
   $getUsersQuery.= " AND user_type = '$userType'"; 
  }
  
  $getUsersResult = mysqli_query($connection, $getUsersQuery); // Check if users are found 
  if(mysqli_num_rows($getUsersResult)> 0) 
  { $users = mysqli_fetch_all($getUsersResult, MYSQLI_ASSOC); 
  } else {
     $users
= []; 
} ?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="./adminDashboard.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
  </head>
  <body>
    <header>
      <img class="logo" src="../../assets/logo/test.png" alt="" />

      <nav>
        <ul>
          <li>
            <a
              href="http://localhost/freelancing-website/dashboard/admin/adminDashboard.php"
              >Home</a
            >
          </li>
          <li><a href="#">Add Skills</a></li>
        </ul>
        <a
          class="logout"
          href="http://localhost/freelancing-website/dashboard/logout.php"
          >Logout</a
        >
      </nav>
    </header>

    <section class="filter">
      <select name="" id="">
        <option value="">Filter By</option>
        <option value=""><a href="?user_type=client">Client</a></option>
        <option value=""><a href="?user_type=freelancer">Freelancer</a></option>
      </select>
    </section>

    <main>
      <section>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone Number</th>
              <th>User Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
             <?php foreach ($users as $user): ?> 
            <tr>
              <td>
                 <?= $user['user_first_name'] . " " . $user['user_last_name'] ?> 
              </td>
              <td><?= $user['user_email'] ?></td>
              <td><?= $user['user_phone_number'] ?></td>
              <td><?= $user['user_type'] ?></td>
              <td>
                <a
                  href="userVerify.php?user_verification_id=<?= $user['user_id'] ?>&user_type=<?= $user['user_type'] ?>"
                  >View Details</a
                >
              </td>
            </tr>
             <?php endforeach; ?>
            <?php if (empty($users)): ?> 
            <tr>
              <td colspan="5">No users found.</td>
            </tr>
             <?php endif; ?> 
          </tbody>
        </table>
      </section>
    </main>
  </body>
</html>
