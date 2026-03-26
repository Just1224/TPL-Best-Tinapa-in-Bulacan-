<?php
@include 'includes/config.php';
session_start();
?>

<?php include 'header.php'; ?>

<section class="dashboard-section">
    <div class="section-container">
        <div class="section-header">
            <h1 class="section-title">Our Premium Products</h1>
            <p class="section-subtitle">Explore our complete range of smoked fish products, each crafted with traditional Bulacan expertise and modern quality standards.</p>
        </div>

        <div class="products-grid">
            <?php
            $select_services = db_query("SELECT * FROM services");

            if(db_num_rows($select_services) > 0){
                while($row = db_fetch_assoc($select_services)){
            ?>
                <div class="product-card fade-in-up">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                    <div class="product-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <?php if(isset($row['price'])): ?>
                            <div class="product-price"><?php echo number_format($row['price'], 2); ?></div>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <div class="product-actions">
                                <form method="POST" action="cart.php" class="product-quantity">
                                    <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="100">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="product-actions">
                                <a href="login.php?redirect=services.php" class="btn btn-outline">
                                    <i class="fas fa-sign-in-alt"></i> Login to Order
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<div class="dashboard-card fade-in-up" style="grid-column: 1/-1;">
                        <div class="card-content text-center">
                            <i class="fas fa-box-open" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                            <h3>No Products Available</h3>
                            <p>Please check back later for our premium selection.</p>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>