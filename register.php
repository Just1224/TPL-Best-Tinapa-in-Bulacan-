<?php
@include 'includes/config.php';
session_start();

$message = [];

if(isset($_POST['submit'])){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
        $message[] = 'Please fill all fields';
    } elseif(strlen($password) < 6){
        $message[] = 'Password must be at least 6 characters';
    } elseif($password !== $confirm_password){
        $message[] = 'Passwords do not match';
    } else {
        // Check if email already exists in users table
        $check_result = db_query("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        
        if(db_num_rows($check_result) > 0){
            $message[] = 'Email already registered as a user';
        } else {
            // Check if email exists in admin table
            $check_admin = db_query("SELECT id FROM admin WHERE email = :email", ['email' => $email]);
            if(db_num_rows($check_admin) > 0){
                $message[] = 'Email already registered as admin';
            } else {
                // Insert new user
                $insert = db_query("INSERT INTO users (name, email, password, phone, address) VALUES (:name, :email, :password, '', '')", [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                ]);
                
                if($insert){
                    $message[] = 'success:Registration successful! You can now login.';
                } else {
                    $message[] = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Create Account</h2>
        </div>

        <form method="POST" style="max-width: 400px;">
            <?php 
            if(!empty($message)){
                foreach($message as $msg){
                    if(strpos($msg, 'success:') !== false){
                        $display_msg = str_replace('success:', '', $msg);
                        echo '<div class="message" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px; padding: 15px; border-radius: 4px;"><span>'.$display_msg.'</span></div>';
                    } else {
                        echo '<div class="message" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px; padding: 15px; border-radius: 4px;"><span>'.$msg.'</span></div>';
                    }
                }
            }
            ?>
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password (minimum 6 characters)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" name="submit" class="btn btn-full">Register</button>
            
            <p style="text-align: center; margin-top: 20px;">
                Already have an account? <a href="login.php" style="color: var(--primary-color); font-weight: bold;">Login here</a>
            </p>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>

            <button type="submit" name="submit" class="btn btn-full">Register</button>
            
            <p style="text-align: center; margin-top: 20px;">
                Already have an account? <a href="login.php" style="color: var(--primary-color); font-weight: bold;">Login here</a>
            </p>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
