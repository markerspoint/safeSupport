<?php include('../includes/header.php'); ?>

<main class="bg-light">

<!-- Hero Section -->
<section class="position-relative py-5" style="background:rgb(227, 228, 235);">
    <div class="container d-flex align-items-center">
        <!-- Text Section (Left) -->
        <div class="text-center text-white w-100 w-md-6 animate-scroll">
            <h1 class="display-4 fw-semibold mb-4" style="color: #e3b766;">Welcome to SafeSupport</h1>
            <p class="fs-4 mb-4" style="color: #333333;">We provide a reliable scheduling system for counseling services, making it easier for you to connect with the right support.</p>
            <p class="fs-5 fst-italic" style="color: #e3b766;">"SafeSupport: We Listen at Your Concern"</p>
            <a href="../public/register.php" class="btn" 
                style="background-color: #e3b766; color: #fff; transition: transform 0.3s ease, background-color 0.3s ease;"
                onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#c1703d';"
                onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#e3b766';">
                Get Started
            </a>
        </div>
        
        <!-- Image Section (Right) -->
        <div class="w-100 w-md-6 animate-scroll">
            <img src="../images/herosection.png" alt="Hero Section Image" class="img-fluid">
        </div>
    </div>
</section>




<!-- About Us Section -->
<section class="py-5" style="background:rgb(227, 228, 235); padding-left: 0; padding-right: 0;">
    <h2 class="text-center display-5 fw-semibold text-dark animate-scroll" style="margin-bottom: 30px;">About SafeSupport</h2>
    <div class="container text-center">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/counseling.png" class="card-img-top rounded mb-3" alt="Counseling Image">
                    <p class="fs-5 text-dark">At SafeSupport, we offer an intuitive scheduling system for counseling services, allowing you to book sessions at your convenience.</p>
                </div>
            </div>
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/therapist.png" class="card-img-top rounded mb-3" alt="Therapist Image">
                    <p class="fs-5 text-dark">We connect you with professionals for therapy, mental health support, or career guidance, ensuring help is just a few clicks away.</p>
                </div>
            </div>
            <div class="col-md-4 animate-scroll">
                <div class="card shadow-lg p-4" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    <img src="../images/support.png" class="card-img-top rounded mb-3" alt="Support Image">
                    <a href="/services" class="btn" style="background-color: #e3b766; color: #fff;">Learn More About Our Services</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5" style="background:rgb(227, 228, 235); transition: background 0.5s ease-in-out;">
    <div class="container text-center">
        <h2 class="display-5 fw-semibold text-dark animate-scroll" style="margin-bottom: 30px;">How SafeSupport Works</h2>
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
<section style="background-color: #e3b766; color: white;" class="py-5 text-center">
    <h2 class="display-5 fw-semibold animate-scroll" style="color: #333333;">Ready to Get Started?</h2>
    <p class="fs-4 animate-scroll animate__delay-1s" style="color: #333333;">Scheduling your first counseling session with SafeSupport is just a few steps away.</p>
    <a href="/register" class="btn animate-scroll animate__delay-2s" 
        style="background-color: #ff7f50; color: #333333; transition: transform 0.3s ease, background-color 0.3s ease;"
        onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#c1703d'; this.style.color='#fff';"
        onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#ff7f50'; this.style.color='#333333';">
        Register Now
    </a>
</section>

</main>

<?php include('../includes/footer.php'); ?>
