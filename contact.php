<?php
@include 'includes/config.php';
session_start();

$message = [];

if(isset($_POST['send'])){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $msg = htmlspecialchars($_POST['message']);

    if(empty($name) || empty($email) || empty($msg)){
        $message[] = 'Please fill all required fields';
    } else {
        $insert = mysqli_query($conn, "INSERT INTO messages(name, email, message) VALUES('$name','$email','$msg')");
        if($insert){
            $message[] = 'Message sent successfully! We will contact you soon.';
        } else {
            $message[] = 'Error sending message. Please try again.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Contact Us</h2>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 30px;">Get in Touch</h3>
                
                <div style="margin-bottom: 30px;">
                    <h4><i class="fas fa-phone" style="color: var(--primary-color); margin-right: 10px;"></i> Phone</h4>
                    <p>+63 (999) 999-9999</p>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4><i class="fas fa-envelope" style="color: var(--primary-color); margin-right: 10px;"></i> Email</h4>
                    <p><a href="mailto:info@ptltinapa.com">info@ptltinapa.com</a></p>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4><i class="fas fa-map-marker-alt" style="color: var(--primary-color); margin-right: 10px;"></i> Address</h4>
                    <p>Malolos, Bulacan<br>Philippines</p>
                </div>

                <div style="margin-bottom: 30px;">
                    <h4><i class="fas fa-clock" style="color: var(--primary-color); margin-right: 10px;"></i> Business Hours</h4>
                    <p>Monday - Friday: 9:00 AM - 5:00 PM<br>
                       Saturday: 9:00 AM - 3:00 PM<br>
                       Sunday: Closed</p>
                </div>
            </div>

            <div>
                <form method="POST">
                    <?php 
                    if(!empty($message)){
                        foreach($message as $msg){
                            echo '<div class="message success"><span>'.$msg.'</span></div>';
                        }
                    }
                    ?>
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>

                    <button type="submit" name="send" class="btn btn-full">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>

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