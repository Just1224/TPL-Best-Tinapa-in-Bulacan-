<?php
@include 'includes/config.php';

if(isset($_POST['send'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    mysqli_query($conn, "INSERT INTO message(name, email, message)
                         VALUES('$name','$email','$message')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
</head>
<body>

<h1>Contact Us</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Your Name" required><br><br>
    <input type="email" name="email" placeholder="Your Email" required><br><br>
    <textarea name="message" placeholder="Message" required></textarea><br><br>
    <button type="submit" name="send">Send Message</button>
</form>

<a href="index.php">Home</a>

</body>
</html>