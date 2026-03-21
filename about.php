<?php
@include 'includes/config.php';

// Fetch about content
$select_about = mysqli_query($conn, "SELECT * FROM site_content WHERE section='about'");
$about = mysqli_fetch_assoc($select_about);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="style_users.css">
</head>

<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>About Us</h3>
    <p><a href="index.php">Home</a> / About</p>
</section>

<section class="about">

    <div class="flex">
        <div class="image">
            <img src="images/gigi.jpg" alt="">
        </div>
        <div class="content">
            <h3><?php echo $about['title']; ?></h3>
            <p><?php echo $about['content']; ?></p>
            <a href="services.php" class="btn">View Products</a>
        </div>
    </div>

</section>

<?php @include 'footer.php'; ?>

</body>
</html>