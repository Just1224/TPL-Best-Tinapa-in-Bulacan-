<?php
@include 'includes/config.php';

$select_about = mysqli_query($conn, "SELECT * FROM site_content WHERE section='about'");
$about = mysqli_fetch_assoc($select_about);
?>

<!DOCTYPE html>
<html>
<head>
    <title>About</title>
</head>
<body>

<h1><?php echo $about['title']; ?></h1>
<p><?php echo $about['content']; ?></p>

<a href="index.php">Home</a>

</body>
</html>