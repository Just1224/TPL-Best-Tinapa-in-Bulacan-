<?php
@include 'includes/config.php';
$select_about = mysqli_query($conn, "SELECT * FROM site_content WHERE section='about'");
$about = mysqli_fetch_assoc($select_about);
?>

<?php include 'header.php'; ?>

<section class="dashboard-section">
    <div class="section-container">
        <div class="section-header">
            <h1 class="section-title">About PTL Best Tinapa</h1>
            <p class="section-subtitle">Discover our heritage, commitment to quality, and the story behind Bulacan's finest smoked fish.</p>
        </div>

        <div class="dashboard-grid-2">
            <div class="dashboard-card fade-in-up">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-book-open"></i> Our Story
                    </h3>
                    <p style="line-height: 1.7; margin-bottom: 1rem;"><?php echo $about['content'] ?? 'PTL Best Tinapa has been serving the community with premium quality smoked fish for years. We use traditional smoking methods combined with modern quality control to bring you the finest tinapa products.'; ?></p>
                    <p style="line-height: 1.7;">Every product is made with care and dedication to ensure customer satisfaction and product quality.</p>

                    <div style="margin-top: 2rem;">
                        <h4 style="color: var(--text-primary); margin-bottom: 1rem;">Our Values:</h4>
                        <div style="display: grid; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 24px; height: 24px; background: var(--success-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Quality First</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 24px; height: 24px; background: var(--success-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Customer Satisfaction</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 24px; height: 24px; background: var(--success-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Traditional Methods</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 24px; height: 24px; background: var(--success-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Fresh Ingredients</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card fade-in-up delay-1">
                <div class="card-header" style="background: var(--primary-gradient);">
                    <h2><i class="fas fa-chart-line"></i> Our Achievements</h2>
                </div>
                <div class="card-content">
                    <div style="display: grid; gap: 2rem;">
                        <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-radius: var(--border-radius-lg);">
                            <div style="font-size: 3rem; font-weight: 900; color: var(--primary-color); margin-bottom: 0.5rem;">15+</div>
                            <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary);">Years of Experience</div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.25rem;">Serving Bulacan with pride</div>
                        </div>

                        <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%); border-radius: var(--border-radius-lg);">
                            <div style="font-size: 3rem; font-weight: 900; color: var(--success-color); margin-bottom: 0.5rem;">10K+</div>
                            <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary);">Happy Customers</div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.25rem;">Satisfied families across the region</div>
                        </div>

                        <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%); border-radius: var(--border-radius-lg);">
                            <div style="font-size: 3rem; font-weight: 900; color: var(--warning-color); margin-bottom: 0.5rem;">100%</div>
                            <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary);">Quality Guaranteed</div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.25rem;">Every product meets our standards</div>
                        </div>
                    </div>
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