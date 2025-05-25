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
  <link rel="stylesheet" href="../../assets/css/form-controls.css" />
  <style>
    /* Form Controls Styling */
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .form-control:hover {
      border-color: var(--primary);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    /* Select Dropdown Styling */
    .form-select {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 1rem;
      padding-right: 2.5rem;
      cursor: pointer;
    }

    /* Search Box Styling */
    .search-container {
      position: relative;
      margin-bottom: 1rem;
    }

    .search-container input {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 2.5rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .search-container input:hover {
      border-color: var(--primary);
    }

    .search-container input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    .search-icon {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      width: 1rem;
      height: 1rem;
      color: var(--text-secondary);
      pointer-events: none;
    }

    /* Filter Group Styling */
    .filter-group {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }

    .filter-item {
      flex: 1;
      min-width: 200px;
    }

    .filter-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    /* Active Filters Display */
    .active-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border-color);
    }

    .active-filter {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.25rem 0.75rem;
      background: var(--bg-light);
      border-radius: 1rem;
      font-size: 0.875rem;
      color: var(--text-primary);
    }

    .active-filter button {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 1rem;
      height: 1rem;
      padding: 0;
      border: none;
      background: none;
      color: var(--text-secondary);
      cursor: pointer;
      transition: color 0.2s ease;
    }

    .active-filter button:hover {
      color: var(--danger);
    }

    .clear-filters {
      margin-left: auto;
      font-size: 0.875rem;
      color: var(--primary);
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.25rem 0.5rem;
      transition: color 0.2s ease;
    }

    .clear-filters:hover {
      color: var(--primary-dark);
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link active">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link">Campaigns</a>
        <a href="../chat/chat.php" class="nav-link">Messages</a>
        <a href="influencer_analytics.php" class="nav-link">Analytics</a>
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
  <script>
    let activeFilters = new Map();

    function updateFilters(select) {
      const filterName = select.name || select.id;
      const filterValue = select.value;
      
      if (filterValue) {
        activeFilters.set(filterName, {
          value: filterValue,
          label: select.options[select.selectedIndex].text
        });
      } else {
        activeFilters.delete(filterName);
      }
      
      updateActiveFiltersDisplay();
    }

    function updateActiveFiltersDisplay() {
      const container = document.querySelector('.active-filters');
      if (!container) return;
      
      container.innerHTML = '';
      
      if (activeFilters.size === 0) {
        container.style.display = 'none';
        return;
      }
      
      container.style.display = 'flex';
      
      activeFilters.forEach((filter, name) => {
        const filterElement = document.createElement('div');
        filterElement.className = 'active-filter';
        filterElement.innerHTML = `
          <span>${filter.label}</span>
          <button onclick="removeFilter('${name}')" aria-label="Remove filter">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `;
        container.appendChild(filterElement);
      });

      const clearButton = document.createElement('button');
      clearButton.className = 'clear-filters';
      clearButton.textContent = 'Clear All Filters';
      clearButton.onclick = clearAllFilters;
      container.appendChild(clearButton);
    }

    function removeFilter(filterName) {
      const select = document.querySelector(`select[name="${filterName}"], select#${filterName}`);
      if (select) {
        select.value = '';
        updateFilters(select);
      }
    }

    function clearAllFilters() {
      document.querySelectorAll('.filter-group select').forEach(select => {
        select.value = '';
      });
      activeFilters.clear();
      updateActiveFiltersDisplay();
    }

    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.filter-group select').forEach(select => {
        select.addEventListener('change', () => updateFilters(select));
      });
      updateActiveFiltersDisplay();
    });
  </script>
</body>

</html>