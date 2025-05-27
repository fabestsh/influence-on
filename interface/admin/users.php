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
  <title>User Management - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <style>
    .search-input {
      padding: 0.5rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      font-size: 0.875rem;
      width: 200px;
      transition: all 0.2s ease;
    }
    
    .search-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }
    
    .search-input::placeholder {
      color: var(--text-secondary);
    }
    
    .filter-select {
      padding: 0.5rem 2rem 0.5rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7280' d='M6 8.825L1.175 4 2.05 3.125 6 7.075 9.95 3.125 10.825 4z'/%3E%3C/svg%3E") no-repeat right 0.75rem center;
      color: var(--text-primary);
      font-size: 0.875rem;
      appearance: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .filter-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }
    
    .filter-select option {
      background: var(--bg-white);
      color: var(--text-primary);
    }
    
    .quick-actions {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    
    .quick-actions .button {
      width: 100%;
      justify-content: center;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
    }
    
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border-color);
    }
    
    .card-header .flex {
      gap: 0.75rem;
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
        <a href="users.php" class="nav-link active">Users</a>
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
        <h1 class="welcome-text">User Management</h1>
        <div class="flex gap-4">
          <button class="button button-primary">Export Users</button>
          <button class="button button-primary">Bulk Actions</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Total Users</div>
          <div class="stat-value">45,892</div>
          <div class="stat-change positive">+15% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Businesses</div>
          <div class="stat-value">12,345</div>
          <div class="stat-change positive">+8% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Influencers</div>
          <div class="stat-value">33,547</div>
          <div class="stat-change positive">+18% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Pending Verifications</div>
          <div class="stat-value">156</div>
          <div class="stat-change negative">+23% new requests</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <!-- Businesses Section -->
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Businesses</h2>
              <div class="flex gap-2">
                <input type="text" placeholder="Search businesses..." class="search-input" />
                <select class="filter-select">
                  <option value="all">All Status</option>
                  <option value="active">Active</option>
                  <option value="pending">Pending</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=3" alt="Business logo" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">TechCorp Solutions</div>
                  <div class="list-item-subtitle">
                    Technology & Software
                  </div>
                </div>
                <div class="flex gap-2">
                  <span class="badge badge-success">Active</span>
                  <button class="button button-primary">View Details</button>
                  <button class="button button-danger">Suspend</button>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=4" alt="Business logo" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Fashion Forward</div>
                  <div class="list-item-subtitle">
                    Fashion & Retail
                  </div>
                </div>
                <div class="flex gap-2">
                  <span class="badge badge-warning">Pending</span>
                  <button class="button button-primary">View Details</button>
                  <button class="button button-success">Approve</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Influencers Section -->
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Influencers</h2>
              <div class="flex gap-2">
                <input type="text" placeholder="Search influencers..." class="search-input" />
                <select class="filter-select">
                  <option value="all">All Status</option>
                  <option value="active">Active</option>
                  <option value="pending">Pending</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=1" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Emma Wilson</div>
                  <div class="list-item-subtitle">
                    Fashion & Lifestyle | 500K Followers
                  </div>
                </div>
                <div class="flex gap-2">
                  <span class="badge badge-success">Active</span>
                  <button class="button button-primary">View Details</button>
                  <button class="button button-danger">Suspend</button>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=2" alt="Influencer avatar" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">James Chen</div>
                  <div class="list-item-subtitle">
                    Tech Reviews | 250K Followers
                  </div>
                </div>
                <div class="flex gap-2">
                  <span class="badge badge-warning">Pending</span>
                  <button class="button button-primary">View Details</button>
                  <button class="button button-success">Approve</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="quick-actions">
              <button class="button button-primary">Verify New Users</button>
              <button class="button button-primary">Send Bulk Message</button>
              <button class="button button-primary">Generate Reports</button>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">User Statistics</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Verification Rate</div>
                  <div class="progress-container">
                    <div class="progress-bar">
                      <div class="progress-bar-fill" style="width: 85%"></div>
                    </div>
                  </div>
                </div>
                <span class="badge badge-success">85%</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Active Rate</div>
                  <div class="progress-container">
                    <div class="progress-bar">
                      <div class="progress-bar-fill" style="width: 92%"></div>
                    </div>
                  </div>
                </div>
                <span class="badge badge-success">92%</span>
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