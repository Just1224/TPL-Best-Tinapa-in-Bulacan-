<?php
@include '../includes/config.php';
@include 'includes/auth.php';

// Update content
if(isset($_POST['update'])){
   $section = $_POST['section'];
   $title = $_POST['title'];
   $content = $_POST['content'];

   mysqli_query($conn, "UPDATE site_content SET title='$title', content='$content' WHERE section='$section'") or die('query failed');
}

// Fetch content
$select_content = mysqli_query($conn, "SELECT * FROM site_content") or die('query failed');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Website Content</title>
</head>
<body>

<h1>Edit Website Content</h1>
<a href="dashboard.php">Back to Dashboard</a>
<br><br>

<?php while($row = mysqli_fetch_assoc($select_content)){ ?>

<form method="POST">
    <input type="hidden" name="section" value="<?php echo $row['section']; ?>">

    <label>Section:</label>
    <input type="text" value="<?php echo $row['section']; ?>" disabled>
    <br><br>

    <label>Title:</label>
    <input type="text" name="title" value="<?php echo $row['title']; ?>">
    <br><br>

    <label>Content:</label>
    <textarea name="content" rows="5" cols="50"><?php echo $row['content']; ?></textarea>
    <br><br>

    <button type="submit" name="update">Update</button>
</form>

<hr>

<?php } ?>

</body>
</html>