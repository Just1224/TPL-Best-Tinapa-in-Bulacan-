<?php
@include 'includes/config.php';
$select_about = mysqli_query($conn, "SELECT * FROM site_content WHERE section='about'");
$about = mysqli_fetch_assoc($select_about);
?>

<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>About PTL Best Tinapa</h2>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 20px;">Our Story</h3>
                <p><?php echo $about['content'] ?? 'PTL Best Tinapa has been serving the community with premium quality smoked fish for years. We use traditional smoking methods combined with modern quality control to bring you the finest tinapa products.'; ?></p>
                <p style="margin-top: 15px;">Every product is made with care and dedication to ensure customer satisfaction and product quality.</p>
                
                <div style="margin-top: 30px;">
                    <h4>Our Values:</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> Quality First</li>
                        <li style="padding: 8px 0;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> Customer Satisfaction</li>
                        <li style="padding: 8px 0;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> Traditional Methods</li>
                        <li style="padding: 8px 0;"><i class="fas fa-check" style="color: var(--primary-color); margin-right: 10px;"></i> Fresh Ingredients</li>
                    </ul>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, rgba(196, 30, 58, 0.1) 0%, rgba(139, 0, 0, 0.1) 100%); padding: 40px; border-radius: 8px; text-align: center;">
                <div style="margin-bottom: 30px;">
                    <div style="font-size: 2.5rem; color: var(--primary-color); font-weight: bold;">15+</div>
                    <p>Years of Experience</p>
                </div>
                <div style="margin-bottom: 30px;">
                    <div style="font-size: 2.5rem; color: var(--primary-color); font-weight: bold;">10K+</div>
                    <p>Happy Customers</p>
                </div>
                <div>
                    <div style="font-size: 2.5rem; color: var(--primary-color); font-weight: bold;">100%</div>
                    <p>Quality Guaranteed</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>

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