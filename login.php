<?php
@include 'includes/config.php';
session_start();

$message = [];

if(isset($_POST['submit'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if(empty($email) || empty($password)){
        $message[] = 'Please enter email and password';
    } else {
        // Check in users table (regular users only)
        $select = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $select->bind_param("s", $email);
        $select->execute();
        $result = $select->get_result();
        
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            
            // Simple password check
            if($row['password'] === $password){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_type'] = 'user';
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                header('location: index.php');
                exit();
            } else {
                $message[] = 'Incorrect password';
            }
        } else {
            $message[] = 'Email not found. Please register first.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>User Login</h2>
        </div>

        <form method="POST" style="max-width: 400px;">
            <?php 
            if(!empty($message)){
                foreach($message as $msg){
                    echo '<div class="message" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px; padding: 15px; border-radius: 4px;"><span>'.$msg.'</span></div>';
                }
            }
            ?>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="submit" class="btn btn-full">Login</button>
            
            <p style="text-align: center; margin-top: 20px;">
                Don't have an account? <a href="register.php" style="color: var(--primary-color); font-weight: bold;">Register here</a>
            </p>
            
            <hr style="margin: 20px 0;">
            
            <p style="text-align: center; font-size: 0.9rem; color: var(--text-color);">
                <strong>Admin?</strong> <a href="admin/login.php" style="color: var(--primary-color); font-weight: bold;">Login to Admin Panel</a>
            </p>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
