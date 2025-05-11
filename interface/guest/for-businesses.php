<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For Businesses - InfluenceON</title>
    <link rel="stylesheet" href="../../assets/css/for-businesses.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar">
      <div class="container nav-content">
        <div class="logo">
          <span>Influence</span><span class="logo-on">ON</span>
        </div>
        <ul class="nav-links">
          <li><a href="home.php">Home</a></li>
          <li>
            <a href="for-businesses.php" class="active">For Businesses</a>
          </li>
          <li><a href="for-influencers.php">For Influencers</a></li>
          <li><a href="about-us.php">About Us</a></li>
        </ul>
        <div class="nav-actions">
            <a href="../auth/login.php" class="nav-login">Sign In</a>
            <a href="../auth/register.php" class="nav-register">Get Started</a>
        </div>
        <button class="mobile-nav-toggle" aria-label="Open navigation menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="mobile-nav">
          <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="for-businesses.php"  class="active">For Businesses</a></li>
            <li><a href="for-influencers.php">For Influencers</a></li>
            <li><a href="about-us.php">About Us</a></li>
          </ul>
          <div class="nav-actions">
            <a href="../auth/login.php" class="nav-login">Sign In</a>
            <a href="../auth/register.php" class="nav-register">Get Started</a>
          </div>
        </div>
        <div class="mobile-nav-overlay"></div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero animated section-reveal">
      <div class="container hero-content">
        <div class="hero-text">
          <h1>Grow Your Brand with Top Influencers</h1>
          <p>
            Connect with authentic content creators who align with your brand
            values and help you reach your target audience effectively.
          </p>
          <button class="btn-primary">Get Started Now</button>
        </div>
        <div class="hero-image">
          <img
            src="../../assets/images/business-growth.png"
            alt="Business Collaboration"
          />
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits animated section-reveal">
      <div class="container">
        <h2>Why Choose InfluenceON</h2>
        <div class="benefits-grid">
          <div class="benefit-card">
            <div class="benefit-icon">
              <img
                src="../../assets/images/icons/searchIC.png"
                alt="Target Audience"
              />
            </div>
            <h3>Reach Your Target Audience</h3>
            <p>
              Connect with influencers who have engaged followers in your niche.
            </p>
          </div>
          <div class="benefit-card">
            <div class="benefit-icon">
              <img src="../../assets/images/icons/metrics.png" alt="Analytics" />
            </div>
            <h3>Track Performance</h3>
            <p>
              Monitor campaign metrics and ROI with our comprehensive analytics.
            </p>
          </div>
          <div class="benefit-card">
            <div class="benefit-icon">
              <img src="../../assets/images/icons/wallet.png" alt="Security" />
            </div>
            <h3>Secure Collaboration</h3>
            <p>
              Safe payments and verified influencers ensure smooth partnerships.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works animated section-reveal">
      <div class="container">
        <h2>How It Works</h2>
        <div class="steps">
          <div class="step">
            <div class="step-number">1</div>
            <h3>Find Influencers</h3>
            <p>
              Search and filter through our database of verified influencers.
            </p>
          </div>
          <div class="step">
            <div class="step-number">2</div>
            <h3>Connect & Collaborate</h3>
            <p>Message influencers and discuss campaign details.</p>
          </div>
          <div class="step">
            <div class="step-number">3</div>
            <h3>Launch Campaign</h3>
            <p>Track performance and engage with your audience.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <!-- <section class="testimonials animated section-reveal">
      <div class="container">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-slider">
          <div class="testimonial-card">
            <div class="quote">
              "InfluenceON helped us connect with the perfect influencers for
              our brand. The results exceeded our expectations!"
            </div>
            <div class="author">
              <img src="../assets/images/client1.jpg" alt="Sarah Johnson" />
              <div class="author-info">
                <h4>Sarah Johnson</h4>
                <p>Marketing Director, TechStart</p>
              </div>
            </div>
          </div>
          <div class="testimonial-card">
            <div class="quote">
              "The platform made it easy to find and collaborate with
              influencers who truly understand our brand values."
            </div>
            <div class="author">
              <img src="../assets/images/client2.jpg" alt="Michael Chen" />
              <div class="author-info">
                <h4>Michael Chen</h4>
                <p>CEO, FashionForward</p>
              </div>
            </div>
          </div>
        </div>
        <div class="slider-controls">
          <button class="prev-btn" aria-label="Previous testimonial">←</button>
          <button class="next-btn" aria-label="Next testimonial">→</button>
        </div>
      </div>
    </section> -->

    <!-- Call to Action Section -->
    <section class="cta animated section-reveal">
      <div class="container cta-content">
        <h2>Ready to Grow Your Brand?</h2>
        <p>Join thousands of successful businesses already using InfluenceON</p>
        <button class="btn-primary">Start Your Journey</button>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer animated section-reveal">
      <div class="container footer-content professional-footer">
        <div class="footer-top">
          <div class="footer-brand">
            <span class="logo">Influence<span class="logo-on">ON</span></span>
            <p class="footer-tagline">
              Connecting brands & creators for authentic growth.
            </p>
          </div>
          <nav class="footer-nav">
            <a href="#">Find Influencers</a>
            <a href="#">For Businesses</a>
            <a href="#">For Influencers</a>
            <a href="#">How it Works</a>
          </nav>
          <div class="footer-socials">
            <a href="#" aria-label="Twitter" class="footer-social-icon"><i class="fa-brands fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn" class="footer-social-icon"><i class="fa-brands fa-linkedin"></i></a>
            <a href="#" aria-label="Instagram" class="footer-social-icon"><i class="fa-brands fa-instagram"></i></a>
        </div>
        <div class="footer-bottom">
          <span>&copy; 2024 InfluenceON. All rights reserved.</span>
          <span class="footer-links">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
          </span>
        </div>
      </div>
    </footer>

    <script src="../../assets/js/for-businesses.js"></script>
  </body>
</html>
