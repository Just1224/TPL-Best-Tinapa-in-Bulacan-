<?php
@include 'includes/config.php';
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user'){
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = [];

// Fetch user info
$select = db_query("SELECT * FROM users WHERE id = :user_id", ['user_id' => $user_id]);
$user = db_fetch_assoc($select);

// Update profile
if(isset($_POST['update'])){
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $old_password = htmlspecialchars($_POST['old_password']);
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if(!empty($name)){
        $update = db_query("UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :user_id", [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'user_id' => $user_id,
        ]);
        if($update){
            $message[] = 'success:Profile updated successfully!';
            $_SESSION['user_name'] = $name;
        }
    }

    // Change password
    if(!empty($old_password) && !empty($new_password)){
        if($user['password'] !== $old_password){
            $message[] = 'Incorrect old password';
        } elseif(strlen($new_password) < 6){
            $message[] = 'New password must be at least 6 characters';
        } elseif($new_password !== $confirm_password){
            $message[] = 'Passwords do not match';
        } else {
            $update = db_query("UPDATE users SET password = :password WHERE id = :user_id", [
                'password' => $new_password,
                'user_id' => $user_id,
            ]);
            if($update){
                $message[] = 'success:Password changed successfully!';
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>My Profile</h2>
        </div>

        <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
            <!-- Sidebar -->
            <div style="background: var(--light-color); padding: 20px; border-radius: 8px; height: fit-content;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 2rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo $_SESSION['user_name']; ?></h3>
                    <p style="color: var(--text-color); font-size: 0.9rem;">Regular Customer</p>
                </div>
                
                <div style="border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <p style="margin-bottom: 10px;"><strong>Email:</strong></p>
                    <p style="color: var(--text-color); margin-bottom: 15px;"><?php echo $user['email']; ?></p>
                    
                    <?php if(!empty($user['phone'])): ?>
                        <p style="margin-bottom: 10px;"><strong>Phone:</strong></p>
                        <p style="color: var(--text-color); margin-bottom: 15px;"><?php echo $user['phone']; ?></p>
                    <?php endif; ?>
                    
                    <a href="logout.php" class="btn btn-full" style="margin-top: 20px;">Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div>
                <form method="POST">
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

                    <div style="background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                        <h3 style="color: var(--primary-color); margin-bottom: 20px;">Edit Profile</h3>
                        
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email (Cannot be changed)</label>
                            <input type="email" value="<?php echo $user['email']; ?>" disabled style="background: #f0f0f0; color: #999;">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" style="min-height: 100px;"><?php echo $user['address'] ?? ''; ?></textarea>
                        </div>

                        <button type="submit" name="update" class="btn">Update Profile</button>
                    </div>

                    <div style="background: white; padding: 30px; border-radius: 8px;">
                        <h3 style="color: var(--primary-color); margin-bottom: 20px;">Change Password</h3>
                        
                        <div class="form-group">
                            <label for="old_password">Current Password</label>
                            <input type="password" id="old_password" name="old_password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>

                        <small style="color: var(--text-color); display: block; margin-bottom: 15px;">Leave blank if you don't want to change your password</small>
                        <button type="submit" name="update" class="btn">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
