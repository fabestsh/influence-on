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
  <title>Analytics - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="influencer_dashboard.php" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencer_analytics.php" class="nav-link active">Analytics</a>
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
          <h1 class="welcome-text">Performance Analytics</h1>
          <p class="text-secondary">Track your content and campaign performance</p>
        </div>
        <div class="flex gap-2">
          <button class="button">Export Report</button>
          <button class="button button-primary">Custom Analysis</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Total Reach</div>
          <div class="stat-value">1.2M</div>
          <div class="stat-change positive">+15% this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Engagement Rate</div>
          <div class="stat-value">6.8%</div>
          <div class="stat-change positive">+0.5% from average</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Click-through Rate</div>
          <div class="stat-value">3.2%</div>
          <div class="stat-change positive">Above industry average</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Conversion Rate</div>
          <div class="stat-value">2.4%</div>
          <div class="stat-change positive">+0.3% this week</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Performance by Platform</h2>
              <div class="flex gap-2">
                <button class="button button-primary">Last 30 Days</button>
                <button class="button">Custom Range</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=5" alt="Instagram" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Instagram</div>
                  <div class="list-item-subtitle">Primary Platform</div>
                </div>
                <div class="flex gap-4">
                  <div class="text-center">
                    <div class="text-sm text-secondary">Followers</div>
                    <div class="stat-value">850K</div>
                  </div>
                  <div class="text-center">
                    <div class="text-sm text-secondary">Engagement</div>
                    <div class="stat-value">7.2%</div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=6" alt="TikTok" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">TikTok</div>
                  <div class="list-item-subtitle">Growing Platform</div>
                </div>
                <div class="flex gap-4">
                  <div class="text-center">
                    <div class="text-sm text-secondary">Followers</div>
                    <div class="stat-value">250K</div>
                  </div>
                  <div class="text-center">
                    <div class="text-sm text-secondary">Engagement</div>
                    <div class="stat-value">8.5%</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Audience Demographics</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Age Distribution</div>
                  <div class="list-item-subtitle">18-24 (45%), 25-34 (35%), 35+ (20%)</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Top Locations</div>
                  <div class="list-item-subtitle">USA (40%), UK (25%), Canada (15%)</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Growth Metrics</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Follower Growth</div>
                  <div class="stat-value">+5.2K</div>
                  <div class="text-sm text-secondary">This month</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Content Growth</div>
                  <div class="stat-value">+24%</div>
                  <div class="text-sm text-secondary">Month over month</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Performance Alerts</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Trending Content</div>
                  <div class="list-item-subtitle">3 posts performing above average</div>
                </div>
                <span class="badge badge-success">Positive</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Engagement Drop</div>
                  <div class="list-item-subtitle">Minor decrease in story views</div>
                </div>
                <span class="badge badge-warning">Monitor</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../js/script.js"></script>
</body>

</html> 