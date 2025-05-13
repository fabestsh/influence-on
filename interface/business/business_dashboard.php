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
  <title>Business Dashboard - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="#" class="nav-link active">Dashboard</a>
        <a href="#" class="nav-link">Campaigns</a>
        <a href="#" class="nav-link">Influencers</a>
        <a href="#" class="nav-link">Analytics</a>
        <a href="../chat/chat.php" class="nav-link">Chat</a>
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
          <h1 class="welcome-text">Welcome back, TechCo!</h1>
          <p class="text-secondary">
            Here's what's happening with your campaigns
          </p>
        </div>
        <button class="button button-primary">New Campaign</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Campaigns Created</div>
          <div class="stat-value">12</div>
          <div class="stat-change positive">+8% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Budget Spent</div>
          <div class="stat-value">$45.2K</div>
          <div class="stat-change">Total this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Reach</div>
          <div class="stat-value">2.4M</div>
          <div class="stat-change positive">+12% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Influencers Hired</div>
          <div class="stat-value">156</div>
          <div class="stat-change positive">+23 this month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Manager</h2>
              <button class="button button-primary">View All</button>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">
                    Active • 12 influencers
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 75%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 75%"></div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">Active • 8 influencers</div>
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
              <h2 class="card-title">Influencer Applications</h2>
              <div class="flex gap-2">
                <button class="button button-primary">Filter</button>
                <button class="button">Sort</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=5" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Sarah Johnson</div>
                  <div class="list-item-subtitle">
                    Fashion & Lifestyle • 100K followers
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button button-success">Approve</button>
                  <button class="button button-danger">Reject</button>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=6" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Mark Chen</div>
                  <div class="list-item-subtitle">
                    Tech & Gaming • 75K followers
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button button-success">Approve</button>
                  <button class="button button-danger">Reject</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Account Summary</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Billing Status</div>
                  <div class="list-item-subtitle">Enterprise Plan</div>
                </div>
                <span class="badge badge-success">Active</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Next Payment</div>
                  <div class="list-item-subtitle">$999 due May 1</div>
                </div>
                <span class="badge badge-warning">Upcoming</span>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Dispute Reports</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Content Delivery Delay</div>
                  <div class="list-item-subtitle">Tech Review Campaign</div>
                </div>
                <span class="badge badge-warning">Pending</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Payment Issue</div>
                  <div class="list-item-subtitle">Summer Collection</div>
                </div>
                <span class="badge badge-success">Resolved</span>
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