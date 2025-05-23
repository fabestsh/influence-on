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
  <title>Analytics - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencers.php" class="nav-link">Influencers</a>
        <a href="analytics.php" class="nav-link active">Analytics</a>
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
          <h1 class="welcome-text">Analytics Overview</h1>
          <p class="text-secondary">Track and analyze your campaign performance</p>
        </div>
        <div class="flex gap-2">
          <select class="input">
            <option>Last 30 Days</option>
            <option>Last 90 Days</option>
            <option>Last Year</option>
            <option>Custom Range</option>
          </select>
          <button class="button button-primary">Export Report</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Total Reach</div>
          <div class="stat-value">4.2M</div>
          <div class="stat-change positive">+15% from last period</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Engagement Rate</div>
          <div class="stat-value">3.8%</div>
          <div class="stat-change positive">+0.4% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Conversion Rate</div>
          <div class="stat-value">2.1%</div>
          <div class="stat-change positive">+0.3% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">ROI</div>
          <div class="stat-value">285%</div>
          <div class="stat-change positive">+35% from last period</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Performance Trends</h2>
              <div class="flex gap-2">
                <button class="button">Daily</button>
                <button class="button button-primary">Weekly</button>
                <button class="button">Monthly</button>
              </div>
            </div>
            <div class="chart-container">
              <!-- Add performance trend chart here -->
              <div class="placeholder-chart">
                Performance Trends Chart
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Performance</h2>
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
              <!-- More campaign performance items... -->
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Audience Insights</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Top Demographics</div>
                  <div class="list-item-subtitle">
                    Age: 25-34 (45%)<br>
                    Gender: Female (65%)<br>
                    Location: Urban (78%)
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Interests</div>
                  <div class="tags">
                    <span class="tag">Fashion</span>
                    <span class="tag">Technology</span>
                    <span class="tag">Travel</span>
                    <span class="tag">Food</span>
                  </div>
                </div>
              </div>
            </div>
          </div>