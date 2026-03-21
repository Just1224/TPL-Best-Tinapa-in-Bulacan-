<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// Delete message
if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);

   $stmt = $conn->prepare("DELETE FROM message WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();

   header('location:messages.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Admin - Messages</title>
</head>
<body>

<h1>Customer Messages</h1>
<a href="dashboard.php">Back to Dashboard</a>

<hr>

<?php
$select_message = mysqli_query($conn, "SELECT * FROM message ORDER BY id DESC");

if(mysqli_num_rows($select_message) > 0){
   while($fetch_message = mysqli_fetch_assoc($select_message)){
?>

<div style="border:1px solid #000; padding:10px; margin:10px;">
   <p>Name: <?php echo htmlspecialchars($fetch_message['name']); ?></p>
   <p>Email: <?php echo htmlspecialchars($fetch_message['email']); ?></p>
   <p>Message: <?php echo htmlspecialchars($fetch_message['message']); ?></p>

   <a href="messages.php?delete=<?php echo $fetch_message['id']; ?>" 
      onclick="return confirm('Delete this message?');">
      Delete
   </a>
</div>

<?php
   }
}else{
   echo "<p>No messages found.</p>";
}
?>

</body>
</html>