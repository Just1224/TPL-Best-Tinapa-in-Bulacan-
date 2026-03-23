<?php
$page_title = "Dashboard";
@include '../includes/config.php';
@include 'header_admin.php';

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
};
?>

<div class="admin-section">
   <h2><i class="fas fa-chart-line"></i> Dashboard Overview</h2>
   
   <div class="box-container">

      <!-- Total Services -->
      <div class="box">
         <?php
            $select_services = mysqli_query($conn, "SELECT * FROM `services`") or die('query failed');
            $number_of_services = mysqli_num_rows($select_services);
         ?>
         <h3><?php echo $number_of_services; ?></h3>
         <p>Total Products</p>
         <a href="services.php" class="btn btn-small">Manage</a>
      </div>

      <!-- Website Content -->
      <div class="box">
         <?php
            $select_content = mysqli_query($conn, "SELECT * FROM `site_content`") or die('query failed');
            $number_of_content = mysqli_num_rows($select_content);
         ?>
         <h3><?php echo $number_of_content; ?></h3>
         <p>Content Sections</p>
         <a href="edit_content.php" class="btn btn-small">Edit</a>
      </div>

      <!-- Users -->
      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>Registered Users</p>
         <a href="users.php" class="btn btn-small">View</a>
      </div>

      <!-- Messages -->
      <div class="box">
         <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `messages`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>Contact Messages</p>
         <a href="messages.php" class="btn btn-small">View</a>
      </div>

   </div>
</div>

<div class="admin-section">
   <h2><i class="fas fa-cog"></i> Quick Actions</h2>
   
   <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
      <a href="services.php" class="btn" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; background: linear-gradient(135deg, #3498db, #2980b9);">
         <i class="fas fa-plus" style="font-size: 1.5rem;"></i>
         Add New Product
      </a>
      
      <a href="edit_content.php" class="btn" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; background: linear-gradient(135deg, #27ae60, #229954);">
         <i class="fas fa-edit" style="font-size: 1.5rem;"></i>
         Edit Website Content
      </a>
      
      <a href="messages.php" class="btn" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; background: linear-gradient(135deg, #e74c3c, #c0392b);">
         <i class="fas fa-envelope" style="font-size: 1.5rem;"></i>
         View Messages
      </a>
      
      <a href="../index.php" class="btn" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px; background: linear-gradient(135deg, #95a5a6, #7f8c8d);">
         <i class="fas fa-globe" style="font-size: 1.5rem;"></i>
         View Website
      </a>
   </div>
</div>

<?php @include 'footer_admin.php'; ?>

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