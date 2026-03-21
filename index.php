<?php
@include 'includes/config.php';
session_start();
?>

<?php include 'header.php'; ?>

<section class="home">
    <h1>Welcome to PTL Tinapa Store</h1>
</section>

<?php include 'footer.php'; ?>

// Fetch homepage content
$select_home = mysqli_query($conn, "SELECT * FROM site_content WHERE section='home'");
$home = mysqli_fetch_assoc($select_home);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tinapa Store</title>
</head>
<body>

<h1><?php echo $home['title']; ?></h1>
<p><?php echo $home['content']; ?></p>

<a href="about.php">About</a> |
<a href="services.php">Services</a> |
<a href="contact.php">Contact</a>

<hr>

<h2>Our Services / Products</h2>

<?php
$select_services = mysqli_query($conn, "SELECT * FROM services");

while($row = mysqli_fetch_assoc($select_services)){
?>
    <div>
        <img src="uploads/<?php echo $row['image']; ?>" width="150">
        <h3><?php echo $row['title']; ?></h3>
        <p><?php echo $row['description']; ?></p>
    </div>
    <hr>
<?php } ?>

</body>
</html>