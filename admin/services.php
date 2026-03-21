<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// ADD SERVICE
if(isset($_POST['add_service'])){
   $title = $_POST['title'];
   $description = $_POST['description'];

   $image = $_FILES['image']['name'];
   $image_tmp = $_FILES['image']['tmp_name'];
   $image_folder = '../uploads/'.$image;

   mysqli_query($conn, "INSERT INTO services(title, description, image) VALUES('$title','$description','$image')") or die('query failed');

   move_uploaded_file($image_tmp, $image_folder);
}

// DELETE SERVICE
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM services WHERE id = '$delete_id'") or die('query failed');
   header('location:services.php');
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Manage Services</title>
</head>
<body>

<h1>Manage Services</h1>
<a href="dashboard.php">Back to Dashboard</a>

<h2>Add New Service</h2>

<form action="" method="POST" enctype="multipart/form-data">
   <input type="text" name="title" placeholder="Service Title" required>
   <br><br>
   <textarea name="description" placeholder="Service Description" required></textarea>
   <br><br>
   <input type="file" name="image" required>
   <br><br>
   <button type="submit" name="add_service">Add Service</button>
</form>

<hr>

<h2>All Services</h2>

<?php
$select_services = mysqli_query($conn, "SELECT * FROM services") or die('query failed');
if(mysqli_num_rows($select_services) > 0){
   while($fetch_services = mysqli_fetch_assoc($select_services)){
?>

<div style="border:1px solid #000; padding:10px; margin:10px;">
   <img src="../uploads/<?php echo $fetch_services['image']; ?>" width="100">
   <h3><?php echo $fetch_services['title']; ?></h3>
   <p><?php echo $fetch_services['description']; ?></p>
   <a href="services.php?delete=<?php echo $fetch_services['id']; ?>">Delete</a>
</div>

<?php
   }
}else{
   echo "<p>No services added yet.</p>";
}
?>

</body>
</html>