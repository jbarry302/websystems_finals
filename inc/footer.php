<footer>
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>Pproviding high-quality online learning resources to empower individuals. Join our community
                    today and unlock your potential with AcademiaHub.</p>
                <br>
                <p>
                    <a href="privacy-policy.php">Privacy Policy</a> | <a href="terms-of-service.php">Terms of Service</a> | <a href="cookie-policy.php">Cookie
                        Policy</a>
                </p>
            </div>
            <!-- <div class="footer-section">
                <h4>Other Services</h4>
                <ul>
                    <li><a href="#">Service 1</a></li>
                    <li><a href="#">Service 2</a></li>
                    <li><a href="#">Service 3</a></li>
                </ul>
            </div> -->
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>Email: info@academiahub.com</p>
                <p>Phone: +1234567890</p>
                <p>Address: 123 Main Street, City, Country</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> AcademiaHub. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php include('cookie-dialog.php'); ?>

<script>
    const userMenu = document.getElementById("user-menu");

    function toggleUserMenu() {
        if (userMenu.style.display === "block" || window.innerWidth < 768) {
            userMenu.style.display = "none";
        } else {
            userMenu.style.display = "block";
        }
    }
</script>

<link rel="stylesheet" href="assets/css/footer.css">