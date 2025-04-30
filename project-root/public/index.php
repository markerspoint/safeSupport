<?php include('../includes/header.php'); ?>

<main style="background: #ffffff;">
    

<!-- Hero Section -->
<section class="py-5 min-vh-100 d-flex align-items-center" style="background: #ffffff;">
    <div class="hero-container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-6 text-center text-md-start">
                <h1 class="display-3 fw-bold mb-4 animate-scroll" style="color: #333333;">
                    Welcome to <span style="color: #0b6043;">Safe</span>Support
                </h1>
                <p class="lead mb-5 animate-scroll" style="color: #666666; font-size: clamp(1rem, 5vw, 1.5rem); line-height: 1.6;">
                    Your trusted platform for scheduling counseling services and mental health support.
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-md-start animate-scroll">
                    <a href="../public/login.php" class="btn btn-lg px-5 py-3" 
                        style="background-color: #0b6043; color: #fff; transition: all 0.3s ease; font-size: 1.2rem;"
                        onmouseover="this.style.backgroundColor='#022116';"
                        onmouseout="this.style.backgroundColor='#0b6043';">
                        Get Started
                    </a>
                    <a href="#HowItWorks" class="btn btn-lg btn-outline-secondary px-5 py-3" style="font-size: 1.2rem;">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="img-mhs p-4 text-center animate-scroll">
                    <img src="../images/mentalhealth.png" alt="Mental Health Support" class="img-fluid" 
                        style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Announcement section -->
<!-- make the announcement in different page make
 it only accessible into the navbar -->


<!-- About Us Section -->
<section class="py-5" style="background: #ffffff; padding-left: 0; padding-right: 0;">
    <h2 class="text-center display-5 fw-bold text-dark animate-scroll" style="margin-bottom: 30px;">
        <span style="color: #333333;">About </span><span style="color: #0b6043;">Safe</span><span style="color: #333333;">Support</span>
    </h2>
    <div class="container text-center">
        <div class="row g-4 justify-content-center">
            <!-- First Card -->
            <div class="col-md-4 animate-scroll"> <!-- Changed from col-md-3 to col-md-4 -->
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease; max-height: 60vh;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/counseling.png" class="card-img-top rounded mb-3" alt="Online Counseling Scheduling System" style="height: 160px; object-fit: contain;">
                    <p class="fs-6 text-dark">At SafeSupport, we offer an intuitive scheduling system for counseling services, allowing you to book sessions at your convenience.</p>
                </div>
            </div>
            <!-- Second Card -->
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease; max-height: 60vh;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/therapist.png" class="card-img-top rounded mb-3" alt="Professional Mental Health Support" style="height: 160px; object-fit: contain;">
                    <p class="fs-6 text-dark">We connect you with professionals for therapy, mental health support, or career guidance, ensuring help is just a few clicks away.</p>
                </div>
            </div>
            <!-- Third Card -->
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease; max-height: vh;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/support.png" class="card-img-top rounded mb-3" alt="24/7 Mental Health Support Services" style="height: 160px; object-fit: contain;">
                    <a href="/services" class="btn mt-3" 
                        style="background-color: #0b6043; color: #fff; transition: transform 0.3s ease, background-color 0.3s ease;"
                        onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#c1703d';"
                        onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#0b6043';">
                        Learn More About Our Services
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="HowItWorks" class="py-5" style="background: #ffffff; transition: background 0.5s ease-in-out;">
    <div class="container text-center">
        <h2 class="display-5 fw-bold text-dark animate-scroll" style="margin-bottom: 30px;">How <span style="color: #0b6043;">Safe</span>Support Works</h2>
        <div class="row g-4">
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <div class="fs-1" style="color: #c1703d;"><i class="fas fa-user"></i></div>
                    <h3 class="fs-4 fw-semibold" style="color: #333333;">1. Choose Your Counselor</h3>
                    <p style="color: #333333;">Browse our professional counselors and choose the one that suits your needs.</p>
                </div>
            </div>
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <div class="fs-1" style="color: #c1703d;"><i class="fas fa-calendar-check"></i></div>
                    <h3 class="fs-4 fw-semibold text-dark">2. Schedule Your Session</h3>
                    <p class="text-dark">Pick a convenient time with flexible scheduling options.</p>
                </div>
            </div>
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <div class="fs-1" style="color: #c1703d;"><i class="fas fa-headset"></i></div>
                    <h3 class="fs-4 fw-semibold text-dark">3. Receive Support</h3>
                    <p class="text-dark">Your counselor will be ready to listen and support you.</p>
                </div>
            </div>
        </div>
    </div>
</section> 

<!-- Call to Action Section -->
<section style="background-color: #0b6043; color: white;" class="py-5 text-center">
    <h2 class="display-5 fw-semibold animate-scroll" style="color: #ffffff;">Ready to Get Started?</h2>
    <p class="fs-4 animate-scroll animate__delay-1s" style="color: #ffffff;">Scheduling your first counseling session with SafeSupport is just a few steps away.</p>
    <a href="../public/register.php" class="btn animate-scroll animate__delay-2s" 
        style="background-color:#98f1d2; color: #333333; transition: transform 0.3s ease, background-color 0.3s ease;"
        onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#98f1d2'; this.style.color='#fff';"
        onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#98f1d2'; this.style.color='#333333';">
        Register Now
    </a>
</section>

</main>

<?php include('../includes/footer.php'); ?>
<script src="../assets/js/index.js"></script>

