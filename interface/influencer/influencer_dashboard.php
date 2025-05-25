<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'influencer' || $_SESSION['status'] != 1) {
  header('Location: ../auth/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Influencer Dashboard - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="#" class="nav-link active">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencer_analytics.php" class="nav-link">Analytics</a>
        <a href="influencer_messages.php" class="nav-link">Messages</a>
        <a href="influencer_profile.php" class="nav-link">Profile</a>
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
          <h1 class="welcome-text">Welcome back, Infuencer!</h1>
          <p class="text-secondary">Here's how your content is performing</p>
        </div>
        <button class="button button-primary">Create Content</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Campaigns Joined</div>
          <div class="stat-value">12</div>
          <div class="stat-change positive">+3 this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Earnings</div>
          <div class="stat-value">$45.2K</div>
          <div class="stat-change positive">+15% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Engagement Rate</div>
          <div class="stat-value">5.2%</div>
          <div class="stat-change positive">+0.8% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Profile Views</div>
          <div class="stat-value">2.4K</div>
          <div class="stat-change positive">+12% this week</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Recent Campaigns</h2>
              <button class="button button-primary">View All</button>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=3" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">with Fashion Brand X</div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 75%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 75%"></div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=4" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">with TechCo</div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 45%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 45%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Content Performance</h2>
              <div class="flex gap-2">
                <button class="button button-primary">Last 7 Days</button>
                <button class="button">Last 30 Days</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="post1.jpg" alt="Post thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Summer Fashion Tips</div>
                  <div class="list-item-subtitle">Posted 2 days ago</div>
                </div>
                <div class="flex gap-4">
                  <div class="text-center">
                    <div class="text-sm text-secondary">Likes</div>
                    <div class="stat-value">1.2K</div>
                  </div>
                  <div class="text-center">
                    <div class="text-sm text-secondary">Comments</div>
                    <div class="stat-value">234</div>
                  </div>
                  <div class="text-center">
                    <div class="text-sm text-secondary">Reach</div>
                    <div class="stat-value">15.4K</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Earnings Breakdown</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Campaign Revenue</div>
                  <div class="stat-value">$32.5K</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Affiliate Sales</div>
                  <div class="stat-value">$8.7K</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Brand Partnerships</div>
                  <div class="stat-value">$4K</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">System Status</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Profile Status</div>
                  <div class="list-item-subtitle">Verified Creator</div>
                </div>
                <span class="badge badge-success">Active</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Next Payout</div>
                  <div class="list-item-subtitle">Scheduled for May 1</div>
                </div>
                <span class="badge badge-warning">Pending</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../js/scriptt.js"></script>
</body>

</html>