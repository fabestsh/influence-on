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
  <title>Campaigns - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="campaigns.php" class="nav-link active">Campaigns</a>
        <a href="influencers.php" class="nav-link">Influencers</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
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
          <p class="text-secondary">Create and manage your influencer marketing campaigns</p>
        </div>
        <div class="flex gap-2">
          <button class="button">Filter Campaigns</button>
          <button class="button button-primary">Create Campaign</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Campaigns</div>
          <div class="stat-value">8</div>
          <div class="stat-change positive">+2 from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Budget</div>
          <div class="stat-value">$85.5K</div>
          <div class="stat-change">Allocated</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Campaign Reach</div>
          <div class="stat-value">3.2M</div>
          <div class="stat-change positive">+18% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Success Rate</div>
          <div class="stat-value">92%</div>
          <div class="stat-change positive">+5% from target</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Campaigns</h2>
              <div class="flex gap-2">
                <button class="button">All</button>
                <button class="button button-primary">Active</button>
                <button class="button">Draft</button>
                <button class="button">Completed</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">
                    Active • 12 influencers • $25K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Fashion</span>
                    <span class="tag">Lifestyle</span>
                    <span class="tag">High Priority</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 75%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 75%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button">View Details</button>
                  <button class="button button-primary">Manage</button>
                </div>
              </div>

              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">
                    Active • 8 influencers • $15K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Technology</span>
                    <span class="tag">Gaming</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 45%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 45%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button">View Details</button>
                  <button class="button button-primary">Manage</button>
                </div>
              </div>

              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Holiday Special</div>
                  <div class="list-item-subtitle">
                    Draft • 15 influencers • $35K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Seasonal</span>
                    <span class="tag">Shopping</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Not Started</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 0%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button">Edit</button>
                  <button class="button button-primary">Launch</button>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Performance</h2>
              <div class="flex gap-2">
                <select class="input">
                  <option>Last 7 Days</option>
                  <option>Last 30 Days</option>
                  <option>Last 90 Days</option>
                </select>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">
                    Reach: 1.2M • Engagement: 4.5% • ROI: 320%
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Performance: 92%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 92%"></div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">
                    Reach: 850K • Engagement: 3.8% • ROI: 280%
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Performance: 85%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 85%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Categories</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Fashion & Beauty</div>
                  <div class="list-item-subtitle">4 active campaigns</div>
                </div>
                <span class="badge badge-primary">Popular</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech & Gaming</div>
                  <div class="list-item-subtitle">2 active campaigns</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Food & Travel</div>
                  <div class="list-item-subtitle">1 active campaign</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="list">
              <button class="button button-primary full-width mb-2">Create New Campaign</button>
              <button class="button full-width mb-2">Import Campaign</button>
              <button class="button full-width">Export Campaign Data</button>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Upcoming Deadlines</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">Content due in 3 days</div>
                </div>
                <span class="badge badge-warning">Urgent</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">Review due in 5 days</div>
                </div>
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