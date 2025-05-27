<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>InfluenceON - Connect with Influencers</title>
    <link rel="stylesheet" href="../../assets/css/home.css" />
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
          <a href="#" class="nav-register">Get Started</a>
        </div>
        <button class="mobile-nav-toggle" aria-label="Open navigation menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="mobile-nav">
          <ul class="nav-links">
            <li><a href="home.php" class="active">Home</a></li>
            <li><a href="for-businesses.php">For Businesses</a></li>
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
          <h1>Connect with the Perfect Influencers for Your Brand</h1>
          <p>
            Find, collaborate, and grow with authentic content creators who
            align with your brand values.
          </p>
          <div class="hero-cta">
            <button class="btn-primary">I'm a Business</button>
            <button class="btn-secondary">I'm an Influencer</button>
          </div>
        </div>
        <div class="hero-image">
          <img
            src="../../assets/images/hero-illustration.png"
            alt="Hero Illustration"
          />
        </div>
      </div>
    </section>

    <!-- Search + Filter Section -->
    <section class="search-filter animated section-reveal">
      <div class="container search-filter-content">
        <input type="text" placeholder="Search by category" />
        <select>
          <option>Engagement Rate</option>
          <option>Followers</option>
          <option>Price</option>
        </select>
        <select>
          <option>Price Range</option>
          <option>$0 - $500</option>
          <option>$500 - $2,000</option>
        </select>
        <button class="btn-primary">Search Influencers</button>
      </div>
    </section>

    <!-- Featured Influencers -->
    <section class="featured-influencers animated section-reveal">
      <div class="container">
        <h2>Featured Influencers</h2>
        <div class="influencer-grid stagger-parent">
          <!-- Influencer Card 1 -->
          <div class="influencer-card stagger-child">
            <img src="../../assets/images/influencerFashion.avif" alt="Sarah Johnson" />
            <div class="card-info">
              <div class="card-header">
                <span class="card-name">Sarah Johnson</span>
                <span class="card-category">Lifestyle & Fashion</span>
                <span class="card-status available">Available</span>
              </div>
              <div class="card-stats">
                <span>Engagement Rate: <b>4.8%</b></span>
                <span>Followers: <b>25K</b></span>
                <span>Rate: <b>$1.2K</b></span>
              </div>
              <button class="btn-secondary">View Profile</button>
            </div>
          </div>
          <!-- Influencer Card 2 -->
          <div class="influencer-card stagger-child">
            <img src="../../assets/images/influencerGaming.avif" alt="Mark Chen" />
            <div class="card-info">
              <div class="card-header">
                <span class="card-name">Mark Chen</span>
                <span class="card-category">Tech & Gaming</span>
                <span class="card-status available">Available</span>
              </div>
              <div class="card-stats">
                <span>Engagement Rate: <b>5.2%</b></span>
                <span>Followers: <b>80K</b></span>
                <span>Rate: <b>$2K</b></span>
              </div>
              <button class="btn-secondary">View Profile</button>
            </div>
          </div>
          <!-- Influencer Card 3 -->
          <div class="influencer-card stagger-child">
            <img src="../../assets/images/influencerEmma.jpg" alt="Emma Roberts" />
            <div class="card-info">
              <div class="card-header">
                <span class="card-name">Emma Roberts</span>
                <span class="card-category">Fitness & Health</span>
                <span class="card-status available">Available</span>
              </div>
              <div class="card-stats">
                <span>Engagement Rate: <b>6.1%</b></span>
                <span>Followers: <b>42K</b></span>
                <span>Rate: <b>$1.8K</b></span>
              </div>
              <button class="btn-secondary">View Profile</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it Works -->
    <section class="how-it-works animated section-reveal">
      <div class="container">
        <h2>How InfluenceON Works</h2>
        <div class="steps stagger-parent">
          <div class="step stagger-child">
            <img src="../../assets/images/icons/search.png" alt="Search" />
            <h3>Search</h3>
            <p>Find the perfect influencer for your brand</p>
          </div>
          <div class="step stagger-child">
            <img src="../../assets/images/icons/connect.png" alt="Connect" />
            <h3>Connect</h3>
            <p>Message and discuss collaboration details</p>
          </div>
          <div class="step stagger-child">
            <img src="../../assets/images/icons/contract.png" alt="Contract" />
            <h3>Contract</h3>
            <p>Secure agreement with escrow payment</p>
          </div>
          <div class="step stagger-child">
            <img src="../../assets/images/icons/start.png" alt="Launch" />
            <h3>Launch</h3>
            <p>Start your successful campaign</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Security Assurance -->
    <section class="security animated section-reveal">
      <div class="container security-content">
        <div class="security-info">
          <h3>Secure Payments & Verified Profiles</h3>
          <p>
            Our platform ensures safe transactions with escrow payments and
            thoroughly verified influencer profiles.
          </p>
          <ul>
            <li>Secure Payment Protection</li>
            <li>Verified Influencer Profiles</li>
            <li>Encrypted Communication</li>
          </ul>
        </div>
        <div class="security-image">
          <img src="../../assets/images/secure.png" alt="Secure Lock" />
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="cta animated section-reveal">
      <div class="container cta-content">
        <h3>Ready to Start Your Influencer Campaign?</h3>
        <p>
          Join thousands of successful brands and influencers already using
          InfluenceON
        </p>
        <div class="cta-buttons">
          <button class="btn-primary">Create Business Account</button>
          <button class="btn-secondary">Become an Influencer</button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer animated section-reveal">
      <div class="container footer-content professional-footer">
        <div class="footer-top">
          <div class="footer-brand">
            <span class="logo">Influence<span class="logo-on">ON</span></span>
            <p class="footer-tagline">Connecting brands & creators for authentic growth.</p>
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
        </div>
        <div class="footer-bottom">
          <span>&copy; 2024 InfluenceON. All rights reserved.</span>
          <span class="footer-links">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
          </span>
        </div>
      </div>
    </footer>

    <script src="../../assets/js/home.js"></script>
  </body>
</html>
