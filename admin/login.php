<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   // Sanitize input
   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $email = mysqli_real_escape_string($conn, $filter_email);
   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_SPECIAL_CHARS);
   $pass = mysqli_real_escape_string($conn, $filter_pass);

   // Fetch the user record
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $row = mysqli_fetch_assoc($select_users);

      // Verify the password
      if(password_verify($pass, $row['password'])){
         
         if($row['user_type'] == 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');
         } elseif($row['user_type'] == 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
         } else {
            $message[] = 'No user found!';
         }

      } else {
         $message[] = 'Incorrect email or password!';
      }

   } else {
      $message[] = 'Incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="icon" href="images/logo2.png" type="image/x-icon"> 

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="style_users.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="submit" class="btn" name="submit" value="Login Now">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>

</section>

</body>
</html>
