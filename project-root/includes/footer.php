<!-- footer.php -->
</main>
<footer class="py-5 mt-auto bg-dark text-light">
    <div class="container text-center">
        <div class="row g-4">
            <!-- First Column (SafeSupport) -->
            <div class="col-lg-3">
                <h5 class="text-light">
                    <i class="fas fa-headset text-success"></i>
                    SafeSupport
                </h5>
                <p class="text-secondary small">
                    We provide a reliable scheduling system for counseling services, helping you connect with the right support when you need it the most.
                </p>
            </div>

            <!-- Second Column (Quick Links) -->
            <div class="col-lg-3">
                <h5 class="text-light">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/public/index.php" class="text-secondary text-decoration-none">Home</a></li>
                    <li><a href="/public/services.php" class="text-secondary text-decoration-none">Services</a></li>
                    <li><a href="/public/about.php" class="text-secondary text-decoration-none">About</a></li>
                    <li><a href="/public/contact.php" class="text-secondary text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Third Column (Customer Service) -->
            <div class="col-lg-3">
                <h5 class="text-light">Customer Service</h5>
                <ul class="list-unstyled">
                    <li><a href="/faq" class="text-secondary text-decoration-none">FAQ</a></li>
                    <li><a href="/privacy-policy" class="text-secondary text-decoration-none">Privacy Policy</a></li>
                    <li><a href="/terms-of-service" class="text-secondary text-decoration-none">Terms of Service</a></li>
                    <li><a href="/support" class="text-secondary text-decoration-none">Support</a></li>
                </ul>
            </div>

            <!-- Fourth Column (Connect with Us) -->
            <div class="col-lg-3">
                <h5 class="text-light">Connect with Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-secondary"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>



        <!-- <div class="conatainer">
            <div class="row text-center">
                <div class="col">
                    <p>
                        <span>Programmer</span> <br>
                        <span class="text-secondary">Mark Ian D. Dela Cruz</span>
                    </p>
                </div>
                <div class="col">
                    <p>
                        <span>Analytical Writer</span> <br>
                        <span class="text-secondary">Mark Ian D. Dela Cruz</span>
                    </p>
                </div>
                <div class="col">
                    <p>
                        <span>Quality and Assurance</span> <br>
                        <span class="text-secondary">Mark Ian D. Dela Cruz</span>
                    </p>
                </div>
                <div class="col">
                    <p>
                        <span>Project Manager</span> <br>
                        <span class="text-secondary">Mark Ian D. Dela Cruz</span>
                    </p>
                </div>
            </div>
        </div> -->



        <!-- Divider -->
        <hr class="my-4 border-secondary">

        <!-- Copyright -->
        <div class="text-center">
            <p class="text-secondary small mb-0">
                &copy; <?= date('Y') ?> SafeSupport. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all elements with the class 'animate-scroll'
    const elements = document.querySelectorAll('.animate-scroll');

    // Create an IntersectionObserver to detect when the element comes into view
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            // Check if the element is in the viewport
            if (entry.isIntersecting) {
                // Add class to trigger the animation
                entry.target.classList.add('animate-scroll-in-view');
            } else {
                // Remove class to reset the element for future animations when it goes out of view
                entry.target.classList.remove('animate-scroll-in-view');
            }
        });
    }, {
        threshold: 0.5  // Trigger the animation when 50% of the element is in view
    });

    // Observe each element
    elements.forEach(element => {
        observer.observe(element);
    });
});
</script>


