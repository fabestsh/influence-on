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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .loading {
      text-align: center;
      padding: 1rem;
      color: var(--text-secondary);
    }

    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }

    .badge {
      display: inline-block;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .badge-success {
      background: var(--success-light);
      color: var(--success);
    }

    .badge-danger {
      background: var(--danger-light);
      color: var(--danger);
    }

    .badge-warning {
      background: var(--warning-light);
      color: var(--warning);
    }
  </style>
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
          <button class="button button-primary" onclick="exportReport()">Export Report</button>
          <button class="button button-primary" onclick="openSystemSettings()">System Settings</button>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Users</div>
          <div class="stat-value" id="active-users">Loading...</div>
          <div class="stat-change positive">+12% from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Pending Verifications</div>
          <div class="stat-value" id="pending-verifications">Loading...</div>
          <div class="stat-change negative">+23% new requests</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Disputes</div>
          <div class="stat-value" id="active-disputes">Loading...</div>
          <div class="stat-change positive">-5% from last week</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Revenue</div>
          <div class="stat-value" id="total-revenue">Loading...</div>
          <div class="stat-change positive">+8% this month</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Verification Queue</h2>
              <button class="button button-primary" onclick="viewAllVerifications()">View All</button>
            </div>
            <div class="list" id="verification-queue">
              <div class="loading">Loading verification queue...</div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Disputes</h2>
              <button class="button button-primary" onclick="viewAllDisputes()">View All</button>
            </div>
            <div class="list" id="active-disputes-list">
              <div class="loading">Loading active disputes...</div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">System Status</h2>
            </div>
            <div class="list" id="system-status">
              <div class="loading">Loading system status...</div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Recent Activity</h2>
            </div>
            <div class="list" id="recent-activity">
              <div class="loading">Loading recent activity...</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Function to format currency
    function formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount);
    }

    // Function to format date
    function formatDate(dateString) {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    }

    // Function to load dashboard stats
    function loadDashboardStats() {
      $.get('php/admin_api.php?action=get_stats', function(response) {
        if (response.success) {
          const data = response.data;
          $('#active-users').text(data.active_users.toLocaleString());
          $('#pending-verifications').text(data.pending_verifications.toLocaleString());
          $('#active-disputes').text(data.active_disputes.toLocaleString());
          $('#total-revenue').text(formatCurrency(data.total_revenue));
        }
      });
    }

    // Function to load verification queue
    function loadVerificationQueue() {
      $.get('php/admin_api.php?action=get_verification_queue', function(response) {
        if (response.success) {
          const queue = response.data;
          const queueHtml = queue.map(user => `
            <div class="list-item">
              <div class="list-item-content">
                <div class="list-item-title">${user.email}</div>
                <div class="list-item-subtitle">
                  ${user.role.charAt(0).toUpperCase() + user.role.slice(1)} - ${user.profile_info}
                </div>
              </div>
              <div class="flex gap-2">
                <button class="button button-success" onclick="handleVerification(${user.id}, 'approve')">Approve</button>
                <button class="button button-danger" onclick="handleVerification(${user.id}, 'reject')">Reject</button>
              </div>
            </div>
          `).join('');
          $('#verification-queue').html(queueHtml || '<div class="empty-state">No pending verifications</div>');
        }
      });
    }

    // Function to handle verification
    function handleVerification(userId, action) {
      if (!confirm(`Are you sure you want to ${action} this user?`)) return;

      $.post('php/admin_api.php?action=handle_verification', {
        user_id: userId,
        action: action
      }, function(response) {
        if (response.success) {
          loadVerificationQueue();
          loadDashboardStats();
        } else {
          alert('Error: ' + response.message);
        }
      });
    }

    // Function to load active disputes
    function loadActiveDisputes() {
      $.get('php/admin_api.php?action=get_disputes', function(response) {
        if (response.success) {
          const disputes = response.data;
          const disputesHtml = disputes.map(dispute => `
            <div class="list-item">
              <div class="list-item-content">
                <div class="list-item-title">${dispute.campaign_title}</div>
                <div class="list-item-subtitle">
                  Reported by: ${dispute.reported_by_email}<br>
                  Reported user: ${dispute.reported_user_email}
                </div>
              </div>
              <button class="button button-primary" onclick="handleDispute(${dispute.id})">Resolve</button>
            </div>
          `).join('');
          $('#active-disputes-list').html(disputesHtml || '<div class="empty-state">No active disputes</div>');
        }
      });
    }

    // Function to handle dispute resolution
    function handleDispute(disputeId) {
      const resolution = prompt('Please enter the resolution details:');
      if (!resolution) return;

      $.post('php/admin_api.php?action=handle_dispute', {
        dispute_id: disputeId,
        action: 'resolve',
        resolution: resolution
      }, function(response) {
        if (response.success) {
          loadActiveDisputes();
          loadDashboardStats();
        } else {
          alert('Error: ' + response.message);
        }
      });
    }

    // Function to load system status
    function loadSystemStatus() {
      $.get('php/admin_api.php?action=get_system_status', function(response) {
        if (response.success) {
          const status = response.data;
          const statusHtml = `
            <div class="list-item">
              <div class="list-item-content">
                <div class="list-item-title">API Status</div>
                <div class="list-item-subtitle">
                  All systems operational
                </div>
              </div>
              <span class="badge badge-${status.api.status === 'operational' ? 'success' : 'danger'}">
                ${status.api.status === 'operational' ? 'Online' : 'Offline'}
              </span>
            </div>
            <div class="list-item">
              <div class="list-item-content">
                <div class="list-item-title">Database</div>
                <div class="list-item-subtitle">Performance normal</div>
              </div>
              <span class="badge badge-${status.database.status === 'operational' ? 'success' : 'danger'}">
                ${status.database.connections} connections
              </span>
            </div>
          `;
          $('#system-status').html(statusHtml);
        }
      });
    }

    // Function to load recent activity
    function loadRecentActivity() {
      $.get('php/admin_api.php?action=get_recent_activity', function(response) {
        if (response.success) {
          const activities = response.data;
          const activityHtml = activities.map(activity => `
            <div class="list-item">
              <div class="list-item-content">
                <div class="list-item-title">
                  ${activity.type === 'user_registration' ? 'New User Registration' : 'Campaign Created'}
                </div>
                <div class="list-item-subtitle">
                  ${activity.description} - ${formatDate(activity.timestamp)}
                </div>
              </div>
            </div>
          `).join('');
          $('#recent-activity').html(activityHtml || '<div class="empty-state">No recent activity</div>');
        }
      });
    }

    // Function to export report
    function exportReport() {
      // Implement report export functionality
      alert('Report export functionality will be implemented soon.');
    }

    // Function to open system settings
    function openSystemSettings() {
      // Implement system settings functionality
      alert('System settings functionality will be implemented soon.');
    }

    // Function to view all verifications
    function viewAllVerifications() {
      window.location.href = 'users.php?filter=pending';
    }

    // Function to view all disputes
    function viewAllDisputes() {
      window.location.href = 'disputes.php';
    }

    // Load all data when the page loads
    $(document).ready(function() {
      loadDashboardStats();
      loadVerificationQueue();
      loadActiveDisputes();
      loadSystemStatus();
      loadRecentActivity();

      // Refresh data every 5 minutes
      setInterval(function() {
        loadDashboardStats();
        loadVerificationQueue();
        loadActiveDisputes();
        loadSystemStatus();
        loadRecentActivity();
      }, 300000);
    });
  </script>
</body>

</html>