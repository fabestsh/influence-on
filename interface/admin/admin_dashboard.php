<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'admin' || $_SESSION['status'] != 1) 
{
    header('Location: ../auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="admin_dashboard.php" class="nav-link active">Dashboard</a>
        <a href="users.php" class="nav-link">Users</a>
        <a href="disputes.php" class="nav-link">Disputes</a>
        <a href="reports.php" class="nav-link">Reports</a>
        <form method="POST" action="../../interface/auth/php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="dashboard-header">
        <h1 class="welcome-text">Admin Dashboard</h1>
        <div class="flex gap-4">
          <button class="button button-primary">Export Report</button>
          <button class="button button-primary">System Settings</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Users</div>
          <div class="stat-value">24,521</div>
          <div class="stat-change positive">+12% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Pending Verifications</div>
          <div class="stat-value">156</div>
          <div class="stat-change negative">+23% new requests</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Disputes</div>
          <div class="stat-value">23</div>
          <div class="stat-change positive">-5% from last week</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Revenue</div>
          <div class="stat-value">$1.2M</div>
          <div class="stat-change positive">+8% this month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Verification Queue</h2>
              <button class="button button-primary">View All</button>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=1" alt="User avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Emma Wilson</div>
                  <div class="list-item-subtitle">
                    Fashion & Lifestyle Influencer
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button button-success">Approve</button>
                  <button class="button button-danger">Reject</button>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=2" alt="User avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">James Chen</div>
                  <div class="list-item-subtitle">Tech Reviewer</div>
                </div>
                <div class="flex gap-2">
                  <button class="button button-success">Approve</button>
                  <button class="button button-danger">Reject</button>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Disputes</h2>
              <button class="button button-primary">View All</button>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Payment Dispute #2234</div>
                  <div class="list-item-subtitle">
                    Campaign: Summer Collection
                  </div>
                </div>
                <span class="badge badge-warning">High Priority</span>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">System Status</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">API Status</div>
                  <div class="list-item-subtitle">
                    All systems operational
                  </div>
                </div>
                <span class="badge badge-success">Online</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Database</div>
                  <div class="list-item-subtitle">Performance normal</div>
                </div>
                <span class="badge badge-success">98.2%</span>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Recent Activity</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">New User Registration</div>
                  <div class="list-item-subtitle">2 minutes ago</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Campaign Created</div>
                  <div class="list-item-subtitle">15 minutes ago</div>
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