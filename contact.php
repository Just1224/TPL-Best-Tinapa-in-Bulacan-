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
        $insert = db_query("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)", [
            'name' => $name,
            'email' => $email,
            'message' => $msg,
        ]);
        if($insert){
            $message[] = 'Message sent successfully! We will contact you soon.';
        } else {
            $message[] = 'Error sending message. Please try again.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="dashboard-section">
    <div class="section-container">
        <div class="section-header">
            <h1 class="section-title">Contact Us</h1>
            <p class="section-subtitle">Get in touch with us for inquiries, orders, or any questions about our premium tinapa products.</p>
        </div>

        <div class="dashboard-grid-2">
            <!-- Contact Information -->
            <div class="dashboard-card fade-in-up">
                <div class="card-header" style="background: var(--success-gradient);">
                    <h2><i class="fas fa-address-book"></i> Get in Touch</h2>
                </div>
                <div class="card-content">
                    <div style="display: flex; flex-direction: column; gap: 2rem;">
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 48px; height: 48px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem; color: var(--text-primary);">Phone</h4>
                                <p style="color: var(--text-secondary); margin: 0;">+63 (999) 999-9999</p>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">Mon-Fri: 9AM-5PM</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 48px; height: 48px; background: var(--secondary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem; color: var(--text-primary);">Email</h4>
                                <p style="color: var(--text-secondary); margin: 0;">
                                    <a href="mailto:info@ptltinapa.com" style="color: var(--primary-color); text-decoration: none;">info@ptltinapa.com</a>
                                </p>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">We'll respond within 24 hours</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 48px; height: 48px; background: var(--warning-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem; color: var(--text-primary);">Address</h4>
                                <p style="color: var(--text-secondary); margin: 0;">Malolos, Bulacan<br>Philippines</p>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">Visit our processing facility</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 48px; height: 48px; background: var(--danger-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem; color: var(--text-primary);">Business Hours</h4>
                                <div style="color: var(--text-secondary);">
                                    <p style="margin: 0.25rem 0;">Monday - Friday: 9:00 AM - 5:00 PM</p>
                                    <p style="margin: 0.25rem 0;">Saturday: 9:00 AM - 3:00 PM</p>
                                    <p style="margin: 0.25rem 0; color: var(--danger-color); font-weight: 600;">Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="dashboard-card fade-in-up delay-1">
                <div class="card-header" style="background: var(--primary-gradient);">
                    <h2><i class="fas fa-paper-plane"></i> Send us a Message</h2>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <?php
                        if(!empty($message)){
                            foreach($message as $msg){
                                if(strpos($msg, 'successfully') !== false){
                                    echo '<div class="alert success"><i class="fas fa-check-circle"></i> ' . $msg . '</div>';
                                } else {
                                    echo '<div class="alert error"><i class="fas fa-exclamation-triangle"></i> ' . $msg . '</div>';
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Tell us how we can help you..." required></textarea>
                        </div>

                        <button type="submit" name="send" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
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