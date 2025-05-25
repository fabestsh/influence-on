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
  <title>Dispute Management - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <style>
    .dispute-thread {
      background: var(--bg-white);
      border-radius: 0.75rem;
      box-shadow: var(--shadow-sm);
      margin-bottom: 1rem;
    }
    
    .dispute-header {
      padding: 1rem;
      border-bottom: 1px solid var(--border-color);
      cursor: pointer;
    }
    
    .dispute-content {
      padding: 1rem;
      display: none;
    }
    
    .dispute-content.active {
      display: block;
    }
    
    .message {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: 0.5rem;
    }
    
    .message-content {
      flex: 1;
    }
    
    .message-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
      color: var(--text-secondary);
      font-size: 0.875rem;
    }
    
    .message-input {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .message-input textarea {
      flex: 1;
      padding: 0.75rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      resize: vertical;
      min-height: 100px;
    }
    
    .priority-high {
      background: rgba(239, 68, 68, 0.1);
      border-left: 4px solid var(--danger);
    }
    
    .priority-medium {
      background: rgba(245, 158, 11, 0.1);
      border-left: 4px solid var(--warning);
    }
    
    .priority-low {
      background: rgba(16, 185, 129, 0.1);
      border-left: 4px solid var(--success);
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
        <a href="disputes.php" class="nav-link active">Disputes</a>
        <a href="reports.php" class="nav-link">Reports</a>
        <form method="POST" action="../php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="dashboard-header">
        <h1 class="welcome-text">Dispute Management</h1>
        <div class="flex gap-4">
          <button class="button button-primary">Export Disputes</button>
          <button class="button button-primary">Resolution Templates</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Disputes</div>
          <div class="stat-value">23</div>
          <div class="stat-change negative">+5 new today</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Resolved This Month</div>
          <div class="stat-value">156</div>
          <div class="stat-change positive">+12% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Average Resolution Time</div>
          <div class="stat-value">2.5 days</div>
          <div class="stat-change positive">-0.5 days from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Satisfaction Rate</div>
          <div class="stat-value">94%</div>
          <div class="stat-change positive">+2% from last month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Disputes</h2>
              <div class="flex gap-2">
                <input type="text" placeholder="Search disputes..." class="search-input" />
                <select class="filter-select">
                  <option value="all">All Priority</option>
                  <option value="high">High Priority</option>
                  <option value="medium">Medium Priority</option>
                  <option value="low">Low Priority</option>
                </select>
              </div>
            </div>
            
            <!-- Dispute Thread Example -->
            <div class="dispute-thread priority-high">
              <div class="dispute-header" onclick="toggleDispute(this)">
                <div class="flex justify-between items-center">
                  <div>
                    <h3 class="text-lg font-semibold">Payment Dispute #2234</h3>
                    <p class="text-sm text-secondary">Campaign: Summer Collection</p>
                  </div>
                  <div class="flex gap-2 items-center">
                    <span class="badge badge-danger">High Priority</span>
                    <span class="badge badge-warning">In Progress</span>
                  </div>
                </div>
              </div>
              
              <div class="dispute-content">
                <div class="message">
                  <img src="https://i.pravatar.cc/150?img=3" alt="Business avatar" class="avatar" />
                  <div class="message-content">
                    <div class="message-meta">
                      <span class="font-medium">TechCorp Solutions</span>
                      <span>2 days ago</span>
                    </div>
                    <p>We have not received the deliverables for our summer campaign despite the agreed deadline. The influencer has been unresponsive to our messages.</p>
                  </div>
                </div>
                
                <div class="message">
                  <img src="https://i.pravatar.cc/150?img=1" alt="Influencer avatar" class="avatar" />
                  <div class="message-content">
                    <div class="message-meta">
                      <span class="font-medium">Emma Wilson</span>
                      <span>1 day ago</span>
                    </div>
                    <p>I apologize for the delay. I was hospitalized for the past week and couldn't complete the deliverables. I can provide medical documentation if needed.</p>
                  </div>
                </div>
                
                <div class="message">
                  <img src="https://i.pravatar.cc/150?img=3" alt="Business avatar" class="avatar" />
                  <div class="message-content">
                    <div class="message-meta">
                      <span class="font-medium">TechCorp Solutions</span>
                      <span>12 hours ago</span>
                    </div>
                    <p>We understand the situation, but we need the content for our campaign launch. Can you provide a new timeline for delivery?</p>
                  </div>
                </div>
                
                <div class="message-input">
                  <textarea placeholder="Type your response..." class="flex-1"></textarea>
                  <button class="button button-primary">Send Response</button>
                </div>
              </div>
            </div>
            
            <!-- Another Dispute Thread -->
            <div class="dispute-thread priority-medium">
              <div class="dispute-header" onclick="toggleDispute(this)">
                <div class="flex justify-between items-center">
                  <div>
                    <h3 class="text-lg font-semibold">Content Dispute #1987</h3>
                    <p class="text-sm text-secondary">Campaign: Product Launch</p>
                  </div>
                  <div class="flex gap-2 items-center">
                    <span class="badge badge-warning">Medium Priority</span>
                    <span class="badge badge-success">Under Review</span>
                  </div>
                </div>
              </div>
              
              <div class="dispute-content">
                <!-- Similar message structure as above -->
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Dispute Statistics</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Resolution Rate</div>
                  <div class="progress-container">
                    <div class="progress-bar">
                      <div class="progress-bar-fill" style="width: 88%"></div>
                    </div>
                  </div>
                </div>
                <span class="badge badge-success">88%</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Response Time</div>
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

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="quick-actions">
              <button class="button button-primary">Create Resolution Template</button>
              <button class="button button-primary">Assign Dispute</button>
              <button class="button button-primary">Generate Report</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function toggleDispute(header) {
      const content = header.nextElementSibling;
      content.classList.toggle('active');
    }
  </script>
  <script src="../js/script.js"></script>
</body>

</html> 