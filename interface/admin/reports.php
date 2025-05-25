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
  <title>Analytics & Reports - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container {
      background: var(--bg-white);
      border-radius: 0.75rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
    }
    
    .chart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .chart-title {
      font-size: 1.125rem;
      font-weight: 600;
      color: var(--text-primary);
    }
    
    .chart-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    .chart-wrapper {
      position: relative;
      height: 300px;
    }
    
    .metric-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .metric-card {
      background: var(--bg-white);
      padding: 1rem;
      border-radius: 0.75rem;
      box-shadow: var(--shadow-sm);
    }
    
    .metric-value {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }
    
    .metric-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }
    
    .metric-trend {
      font-size: 0.875rem;
      margin-top: 0.5rem;
    }
    
    .trend-up {
      color: var(--success);
    }
    
    .trend-down {
      color: var(--danger);
    }
    
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
        <a href="users.php" class="nav-link">Users</a>
        <a href="disputes.php" class="nav-link">Disputes</a>
        <a href="reports.php" class="nav-link active">Reports</a>
        <form method="POST" action="../php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="dashboard-header">
        <h1 class="welcome-text">Analytics & Reports</h1>
        <div class="flex gap-4">
          <button class="button button-primary">Export Data</button>
          <button class="button button-primary">Schedule Report</button>
        </div>
      </div>

      <div class="metric-grid">
        <div class="metric-card">
          <div class="metric-value">$2.4M</div>
          <div class="metric-label">Total Revenue</div>
          <div class="metric-trend trend-up">+15% from last month</div>
        </div>
        <div class="metric-card">
          <div class="metric-value">45.8K</div>
          <div class="metric-label">Active Users</div>
          <div class="metric-trend trend-up">+8% from last month</div>
        </div>
        <div class="metric-card">
          <div class="metric-value">1.2K</div>
          <div class="metric-label">Active Campaigns</div>
          <div class="metric-trend trend-up">+12% from last month</div>
        </div>
        <div class="metric-card">
          <div class="metric-value">94%</div>
          <div class="metric-label">User Satisfaction</div>
          <div class="metric-trend trend-up">+2% from last month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <!-- Revenue Trends -->
          <div class="chart-container">
            <div class="chart-header">
              <h2 class="chart-title">Revenue Trends</h2>
              <div class="chart-actions">
                <select class="filter-select">
                  <option value="monthly">Monthly</option>
                  <option value="quarterly">Quarterly</option>
                  <option value="yearly">Yearly</option>
                </select>
              </div>
            </div>
            <div class="chart-wrapper">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>

          <!-- User Growth -->
          <div class="chart-container">
            <div class="chart-header">
              <h2 class="chart-title">User Growth</h2>
              <div class="chart-actions">
                <select class="filter-select">
                  <option value="all">All Users</option>
                  <option value="businesses">Businesses</option>
                  <option value="influencers">Influencers</option>
                </select>
              </div>
            </div>
            <div class="chart-wrapper">
              <canvas id="userGrowthChart"></canvas>
            </div>
          </div>

          <!-- Campaign Performance -->
          <div class="chart-container">
            <div class="chart-header">
              <h2 class="chart-title">Campaign Performance</h2>
              <div class="chart-actions">
                <select class="filter-select">
                  <option value="all">All Categories</option>
                  <option value="fashion">Fashion</option>
                  <option value="tech">Technology</option>
                  <option value="lifestyle">Lifestyle</option>
                </select>
              </div>
            </div>
            <div class="chart-wrapper">
              <canvas id="campaignChart"></canvas>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Top Performing Categories</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Fashion & Beauty</div>
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
                  <div class="list-item-title">Technology</div>
                  <div class="progress-container">
                    <div class="progress-bar">
                      <div class="progress-bar-fill" style="width: 78%"></div>
                    </div>
                  </div>
                </div>
                <span class="badge badge-success">78%</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Lifestyle</div>
                  <div class="progress-container">
                    <div class="progress-bar">
                      <div class="progress-bar-fill" style="width: 72%"></div>
                    </div>
                  </div>
                </div>
                <span class="badge badge-success">72%</span>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Insights</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Peak Activity Hours</div>
                  <div class="list-item-subtitle">2 PM - 6 PM EST</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Most Active Region</div>
                  <div class="list-item-subtitle">North America (45%)</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Average Campaign Duration</div>
                  <div class="list-item-subtitle">28 days</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Revenue',
          data: [1.2, 1.5, 1.8, 2.0, 2.2, 2.4],
          borderColor: '#6366f1',
          tension: 0.4,
          fill: true,
          backgroundColor: 'rgba(99, 102, 241, 0.1)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => '$' + value + 'M'
            }
          }
        }
      }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Businesses',
          data: [8.5, 9.2, 10.1, 10.8, 11.5, 12.3],
          backgroundColor: '#6366f1'
        }, {
          label: 'Influencers',
          data: [25.2, 27.8, 29.5, 31.2, 32.4, 33.5],
          backgroundColor: '#818cf8'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => value + 'K'
            }
          }
        }
      }
    });

    // Campaign Performance Chart
    const campaignCtx = document.getElementById('campaignChart').getContext('2d');
    new Chart(campaignCtx, {
      type: 'doughnut',
      data: {
        labels: ['Fashion', 'Technology', 'Lifestyle', 'Food', 'Travel'],
        datasets: [{
          data: [35, 25, 20, 12, 8],
          backgroundColor: [
            '#6366f1',
            '#818cf8',
            '#a5b4fc',
            '#c7d2fe',
            '#e0e7ff'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right'
          }
        }
      }
    });
  </script>
  <script src="../js/script.js"></script>
</body>

</html> 