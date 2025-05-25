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
  <title>Campaigns - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="influencer_dashboard.php" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link active">Campaigns</a>
        <a href="influencer_analytics.php" class="nav-link">Analytics</a>
        <a href="influencer_messages.php" class="nav-link">Messages</a>
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
          <h1 class="welcome-text">Campaign Management</h1>
          <p class="text-secondary">Manage your active and upcoming campaigns</p>
        </div>
        <button class="button button-primary">Browse New Campaigns</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Campaigns</div>
          <div class="stat-value">8</div>
          <div class="stat-change positive">2 starting soon</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Completed Campaigns</div>
          <div class="stat-value">24</div>
          <div class="stat-change positive">+3 this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Average Rating</div>
          <div class="stat-value">4.8</div>
          <div class="stat-change positive">Top Performer</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Campaign Earnings</div>
          <div class="stat-value">$12.5K</div>
          <div class="stat-change positive">+22% this month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Campaigns</h2>
              <div class="flex gap-2">
                <button class="button button-primary">Filter</button>
                <button class="button">Sort</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=1" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Summer Fashion Collection</div>
                  <div class="list-item-subtitle">Brand: Fashion Co. | Due: 7 days</div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 65%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 65%"></div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=2" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Fitness App Promotion</div>
                  <div class="list-item-subtitle">Brand: FitLife | Due: 14 days</div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 30%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 30%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Requirements</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Content Guidelines</div>
                  <div class="list-item-subtitle">Review brand requirements and posting schedule</div>
                </div>
                <button class="button">View Details</button>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Submission Deadlines</div>
                  <div class="list-item-subtitle">Track your content submission timeline</div>
                </div>
                <button class="button">View Calendar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Metrics</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Completion Rate</div>
                  <div class="stat-value">95%</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">On-time Delivery</div>
                  <div class="stat-value">98%</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Upcoming Deadlines</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Content Submission</div>
                  <div class="list-item-subtitle">Due in 2 days</div>
                </div>
                <span class="badge badge-warning">Pending</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Campaign Review</div>
                  <div class="list-item-subtitle">Due in 5 days</div>
                </div>
                <span class="badge badge-success">On Track</span>
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