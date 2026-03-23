<footer class="footer">
    <div class="container">
        <div class="box-container">
            <div class="box">
                <h3>Quick Links</h3>
                <a href="index.php"><i class="fas fa-arrow-right"></i> Home</a>
                <a href="about.php"><i class="fas fa-arrow-right"></i> About</a>
                <a href="contact.php"><i class="fas fa-arrow-right"></i> Contact</a>
                <a href="services.php"><i class="fas fa-arrow-right"></i> Products</a>
                <a href="payment_methods.php"><i class="fas fa-arrow-right"></i> Payment Methods</a>
            </div>

            <div class="box">
                <h3>Contact Info</h3>
                <p><i class="fas fa-phone"></i> +63 999 999 9999</p>
                <p><i class="fas fa-envelope"></i> info@ptltinapa.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Malolos, Bulacan, Philippines</p>
            </div>

            <div class="box">
                <h3>Follow Us</h3>
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-tiktok"></i> TikTok</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>

            <div class="box">
                <h3>About PTL</h3>
                <p>PTL Best Tinapa is dedicated to providing the highest quality smoked fish products using traditional methods and the finest ingredients.</p>
            </div>
        </div>

        <div class="credit">
            <p>&copy; Copyright <span class="year"></span> by <span>PTL Best Tinapa in Bulacan</span></p>
            <p style="font-size: 0.9rem; color: #999;">Designed with <i class="fas fa-heart"></i> by Arillano | Elegido | Libunao | Licuanan</p>
        </div>
    </div>
</footer>

<script>
    // Update year in footer
    document.querySelector('.year').textContent = new Date().getFullYear();
    
    // Mobile menu toggle
    const menuBtn = document.getElementById('menu-btn');
    const navbar = document.querySelector('.navbar');
    
    if(menuBtn) {
        menuBtn.addEventListener('click', function() {
            navbar.classList.toggle('active');
        });
    }
</script>