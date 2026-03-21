<?php

@include '../includes/config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

   <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>

<h1 style="text-align:center;">Admin Dashboard</h1>

<div class="box-container" style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">

   <!-- Total Services -->
   <div class="box">
      <?php
         $select_services = mysqli_query($conn, "SELECT * FROM `services`") or die('query failed');
         $number_of_services = mysqli_num_rows($select_services);
      ?>
      <h3><?php echo $number_of_services; ?></h3>
      <p><a href="services.php">Manage Services</a></p>
   </div>

   <!-- Website Content -->
   <div class="box">
      <?php
         $select_content = mysqli_query($conn, "SELECT * FROM `site_content`") or die('query failed');
         $number_of_content = mysqli_num_rows($select_content);
      ?>
      <h3><?php echo $number_of_content; ?></h3>
      <p><a href="edit_content.php">Edit Website Content</a></p>
   </div>

   <!-- Users -->
   <div class="box">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         $number_of_users = mysqli_num_rows($select_users);
      ?>
      <h3><?php echo $number_of_users; ?></h3>
      <p><a href="users.php">Manage Users</a></p>
   </div>

   <!-- Messages -->
   <div class="box">
      <?php
         $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
         $number_of_messages = mysqli_num_rows($select_messages);
      ?>
      <h3><?php echo $number_of_messages; ?></h3>
      <p><a href="messages.php">View Messages</a></p>
   </div>

   <!-- Logout -->
   <div class="box">
      <h3>Account</h3>
      <p><a href="logout.php">Logout</a></p>
   </div>

</div>

</body>
</html>