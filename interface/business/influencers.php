<?php
session_start();

if (
  !isset($_SESSION['authenticated']) ||
  $_SESSION['user_role'] !== 'business' ||
  $_SESSION['status'] != 1
) {
  header('Location: ../auth/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Influencers - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/form-controls.css" />
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencers.php" class="nav-link active">Influencers</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
        <a href="../chat/chat.php" class="nav-link">Messages</a>
        <form method="POST" action="../../interface/auth/php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="dashboard-header">
        <div>
          <h1 class="welcome-text">Influencer Directory</h1>
          <p class="text-secondary">Find and collaborate with the perfect influencers for your brand</p>
        </div>
        <div class="flex gap-2">
          <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="search" placeholder="Search influencers..." class="form-control" />
          </div>
          <button class="button button-primary">Advanced Search</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Total Influencers</div>
          <div class="stat-value">1,234</div>
          <div class="stat-change positive">+156 this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Collaborations</div>
          <div class="stat-value">48</div>
          <div class="stat-change">Current</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Average Engagement</div>
          <div class="stat-value">3.8%</div>
          <div class="stat-change positive">+0.5% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Pending Applications</div>
          <div class="stat-value">23</div>
          <div class="stat-change">To review</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Featured Influencers</h2>
              <div class="flex gap-2">
                <button class="button">Filter by Category</button>
                <button class="button">Sort by Reach</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=1" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Emma Thompson</div>
                  <div class="list-item-subtitle">
                    Fashion & Lifestyle • 250K followers • 4.2% engagement
                  </div>
                  <div class="tags">
                    <span class="tag">Fashion</span>
                    <span class="tag">Lifestyle</span>
                    <span class="tag">Beauty</span>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button">View Profile</button>
                  <button class="button button-primary">Contact</button>
                </div>
              </div>
              <!-- More influencer items... -->
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Recent Applications</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=2" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">James Wilson</div>
                  <div class="list-item-subtitle">
                    Tech & Gaming • 180K followers • Applied 2 days ago
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button button-success">Approve</button>
                  <button class="button button-danger">Reject</button>
                </div>
              </div>
              <!-- More application items... -->
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Categories</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Fashion & Beauty</div>
                  <div class="list-item-subtitle">450 influencers</div>
                </div>
                <span class="badge badge-primary">Popular</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech & Gaming</div>
                  <div class="list-item-subtitle">320 influencers</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Food & Travel</div>
                  <div class="list-item-subtitle">280 influencers</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Top Performing</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=3" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Sophie Chen</div>
                  <div class="list-item-subtitle">5.2% engagement rate</div>
                </div>
                <span class="badge badge-success">Top Rated</span>
              </div>
              <!-- More top performers... -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../js/scriptt.js"></script>
</body>
</html>