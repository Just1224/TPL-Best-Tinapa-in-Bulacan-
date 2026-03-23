<?php
$page_title = "Manage Products";
@include '../includes/config.php';
@include 'header_admin.php';

// ADD SERVICE
if(isset($_POST['add_service'])){
   $title = htmlspecialchars($_POST['title']);
   $description = htmlspecialchars($_POST['description']);
   $price = htmlspecialchars($_POST['price']);

   $image = $_FILES['image']['name'];
   $image_tmp = $_FILES['image']['tmp_name'];
   $image_folder = '../uploads/'.$image;

   $insert = mysqli_query($conn, "INSERT INTO services(title, description, image, price) VALUES('$title','$description','$image','$price')");
   
   if($insert){
      if(move_uploaded_file($image_tmp, $image_folder)){
         $message[] = 'success:Product added successfully!';
      }
   } else {
      $message[] = 'Error adding product!';
   }
}

// DELETE SERVICE
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $select = mysqli_query($conn, "SELECT image FROM services WHERE id = '$delete_id'");
   $row = mysqli_fetch_assoc($select);
   
   if($row){
      if(file_exists('../uploads/'.$row['image'])){
         unlink('../uploads/'.$row['image']);
      }
   }
   
   $delete = mysqli_query($conn, "DELETE FROM services WHERE id = '$delete_id'");
   if($delete){
      $message[] = 'success:Product deleted successfully!';
   }
}

// UPDATE SERVICE
if(isset($_POST['update_service'])){
   $id = $_POST['id'];
   $title = htmlspecialchars($_POST['title']);
   $description = htmlspecialchars($_POST['description']);
   $price = htmlspecialchars($_POST['price']);

   $update = mysqli_query($conn, "UPDATE services SET title='$title', description='$description', price='$price' WHERE id='$id'");
   
   if($update){
      $message[] = 'success:Product updated successfully!';
   }
}

$message = $message ?? [];
?>

<div class="admin-section">
   <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
   
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

   <form action="" method="POST" enctype="multipart/form-data" style="display: grid; gap: 15px;">
      <div>
         <label for="title">Product Title</label>
         <input type="text" id="title" name="title" placeholder="Product Title" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;">
      </div>

      <div>
         <label for="description">Description</label>
         <textarea id="description" name="description" placeholder="Product Description" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 100px;"></textarea>
      </div>

      <div>
         <label for="price">Price (₱)</label>
         <input type="number" id="price" name="price" placeholder="Price" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;">
      </div>

      <div>
         <label for="image">Product Image</label>
         <input type="file" id="image" name="image" accept="image/*" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 4px;">
      </div>

      <button type="submit" name="add_service" class="btn">
         <i class="fas fa-save"></i> Add Product
      </button>
   </form>
</div>

<div class="admin-section">
   <h2><i class="fas fa-list"></i> All Products</h2>
   
   <?php
   $select_services = mysqli_query($conn, "SELECT * FROM services") or die('query failed');
   if(mysqli_num_rows($select_services) > 0){
   ?>
      <table style="width: 100%; border-collapse: collapse;">
         <thead>
            <tr style="background: var(--primary-color); color: white;">
               <th style="padding: 12px; text-align: left; border: 1px solid var(--border-color);">Image</th>
               <th style="padding: 12px; text-align: left; border: 1px solid var(--border-color);">Title</th>
               <th style="padding: 12px; text-align: left; border: 1px solid var(--border-color);">Description</th>
               <th style="padding: 12px; text-align: left; border: 1px solid var(--border-color);">Price</th>
               <th style="padding: 12px; text-align: center; border: 1px solid var(--border-color);">Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php
            while($fetch_services = mysqli_fetch_assoc($select_services)){
            ?>
            <tr style="border-bottom: 1px solid var(--border-color); hover-effect">
               <td style="padding: 12px; border: 1px solid var(--border-color);">
                  <img src="../uploads/<?php echo htmlspecialchars($fetch_services['image']); ?>" width="60" height="60" style="border-radius: 4px; object-fit: cover;">
               </td>
               <td style="padding: 12px; border: 1px solid var(--border-color);">
                  <strong><?php echo htmlspecialchars($fetch_services['title']); ?></strong>
               </td>
               <td style="padding: 12px; border: 1px solid var(--border-color);">
                  <?php echo substr(htmlspecialchars($fetch_services['description']), 0, 50) . '...'; ?>
               </td>
               <td style="padding: 12px; border: 1px solid var(--border-color);">
                  <strong>₱<?php echo number_format($fetch_services['price'], 2); ?></strong>
               </td>
               <td style="padding: 12px; border: 1px solid var(--border-color); text-align: center;">
                  <a href="services.php?delete=<?php echo $fetch_services['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')" style="font-size: 0.85rem; padding: 6px 12px;">
                     <i class="fas fa-trash"></i> Delete
                  </a>
               </td>
            </tr>
            <?php
            }
            ?>
         </tbody>
      </table>
   <?php
   } else {
      echo '<p style="color: var(--text-color); text-align: center; padding: 20px;">No products added yet.</p>';
   }
   ?>
</div>

<?php @include 'footer_admin.php'; ?>