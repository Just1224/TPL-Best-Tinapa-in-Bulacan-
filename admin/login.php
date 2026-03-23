<?php
@include '../includes/config.php';
session_start();

$message = [];

if(isset($_POST['submit'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['pass']);

    if(empty($email) || empty($password)){
        $message[] = 'Please enter email and password';
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $select = $stmt->get_result();
        
        if($select->num_rows > 0){
            $row = $select->fetch_assoc();
            
            // Check if user has admin role
            $is_admin = ($row['role'] === 'admin');
            
            if($is_admin && $row['password'] === $password){
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['user_type'] = 'admin';
                header('location: dashboard.php');
                exit();
            } else if(!$is_admin){
                $message[] = 'Access denied: Only admins can login here';
            } else {
                $message[] = 'Incorrect password';
            }
        } else {
            $message[] = 'Email not found';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login - PTL Best Tinapa</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <style>
      body {
         background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 20px;
      }
      
      .login-container {
         background: white;
         padding: 50px 40px;
         border-radius: 12px;
         box-shadow: 0 10px 40px rgba(0,0,0,0.3);
         width: 100%;
         max-width: 400px;
         text-align: center;
      }
      
      .login-container h2 {
         color: var(--primary-color);
         margin-bottom: 10px;
         font-size: 1.8rem;
      }
      
      .login-container p {
         color: var(--text-color);
         margin-bottom: 30px;
         font-size: 0.9rem;
      }
      
      .login-icon {
         font-size: 3rem;
         color: var(--primary-color);
         margin-bottom: 20px;
      }
   </style>
</head>
<body>

<div class="login-container">
   <div class="login-icon">
      <i class="fas fa-lock"></i>
   </div>
   
   <h2>Admin Login</h2>
   <p>PTL Best Tinapa Management</p>

   <?php
   if(isset($message)){
      foreach($message as $msg){
         echo '<div class="message" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px; padding: 15px; border-radius: 4px; text-align: left;"><span>'.$msg.'</span></div>';
      }
   }
   ?>
   
   <form action="" method="post">
      <div class="form-group">
         <input type="email" name="email" class="form-control" placeholder="Admin Email" required style="margin-bottom: 15px;">
      </div>
      
      <div class="form-group">
         <input type="password" name="pass" class="form-control" placeholder="Password" required>
      </div>
      
      <button type="submit" name="submit" class="btn btn-full" style="margin-top: 20px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
         <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
      </button>
      
      <hr style="margin: 20px 0;">
      
      <p style="margin: 0; font-size: 0.9rem;">
         <a href="../index.php" style="color: var(--primary-color);">
            <i class="fas fa-arrow-left"></i> Back to Website
         </a>
      </p>
   </form>
</div>

</body>
</html>
