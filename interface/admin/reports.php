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
  <title>Reports - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .report-filters {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
      padding: 1rem;
      background: var(--background-light);
      border-radius: 8px;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .filter-group label {
      font-weight: 500;
    }

    .filter-group input,
    .filter-group select {
      padding: 0.5rem;
      border: 1px solid var(--border);
      border-radius: 4px;
    }

    .report-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .report-card {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .report-card h3 {
      margin: 0 0 1rem 0;
      color: var(--text-primary);
    }

    .report-value {
      font-size: 2rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .report-change {
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .change-positive {
      color: var(--success);
    }

    .change-negative {
      color: var(--danger);
    }

    .chart-container {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }

    .chart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .chart-title {
      margin: 0;
      color: var(--text-primary);
    }

    .chart-actions {
      display: flex;
      gap: 0.5rem;
    }

    .table-container {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
    }

    th {
      font-weight: 500;
      color: var(--text-secondary);
      background: var(--background-light);
    }

    tr:hover {
      background: var(--background-light);
    }

    .loading {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }

    .error {
      color: var(--danger);
      margin-bottom: 1rem;
      padding: 1rem;
      background: var(--danger-light);
      border-radius: 4px;
    }

    .success {
      color: var(--success);
      margin-bottom: 1rem;
      padding: 1rem;
      background: var(--success-light);
      border-radius: 4px;
    }

    .export-buttons {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .export-buttons button {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .export-buttons button svg {
      width: 1rem;
      height: 1rem;
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
        <form method="POST" action="../../interface/auth/php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="dashboard-header">
        <h1>Reports</h1>
        <div class="export-buttons">
          <button class="button button-primary" onclick="exportReport('pdf')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Export PDF
          </button>
          <button class="button button-primary" onclick="exportReport('excel')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Export Excel
          </button>
        </div>
      </div>

      <div id="message"></div>

      <div class="report-filters">
        <div class="filter-group">
          <label for="report-type">Report Type</label>
          <select id="report-type" onchange="loadReport()">
            <option value="overview">Overview</option>
            <option value="users">User Analytics</option>
            <option value="revenue">Revenue Report</option>
            <option value="disputes">Dispute Analysis</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="date-range">Date Range</label>
          <select id="date-range" onchange="loadReport()">
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="last7days">Last 7 Days</option>
            <option value="last30days">Last 30 Days</option>
            <option value="thisMonth">This Month</option>
            <option value="lastMonth">Last Month</option>
            <option value="custom">Custom Range</option>
          </select>
        </div>
        <div class="filter-group" id="custom-date-group" style="display: none;">
          <label for="start-date">Start Date</label>
          <input type="date" id="start-date" onchange="loadReport()">
        </div>
        <div class="filter-group" id="custom-date-group-end" style="display: none;">
          <label for="end-date">End Date</label>
          <input type="date" id="end-date" onchange="loadReport()">
        </div>
        <div class="filter-group">
          <label for="group-by">Group By</label>
          <select id="group-by" onchange="loadReport()">
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
          </select>
        </div>
      </div>

      <div id="report-content">
        <div class="loading">Loading report...</div>
      </div>
    </div>
  </main>

  <script>
    function showMessage(message, type) {
      const messageDiv = document.getElementById('message');
      messageDiv.className = type;
      messageDiv.textContent = message;
      setTimeout(() => {
        messageDiv.textContent = '';
        messageDiv.className = '';
      }, 5000);
    }

    function updateDateInputs() {
      const dateRange = document.getElementById('date-range').value;
      const customGroup = document.getElementById('custom-date-group');
      const customGroupEnd = document.getElementById('custom-date-group-end');

      if (dateRange === 'custom') {
        customGroup.style.display = 'flex';
        customGroupEnd.style.display = 'flex';
      } else {
        customGroup.style.display = 'none';
        customGroupEnd.style.display = 'none';
      }
    }

    function getDateRange() {
      const dateRange = document.getElementById('date-range').value;
      const today = new Date();
      let startDate, endDate;

      switch (dateRange) {
        case 'today':
          startDate = endDate = today.toISOString().split('T')[0];
          break;
        case 'yesterday':
          startDate = endDate = new Date(today.setDate(today.getDate() - 1)).toISOString().split('T')[0];
          break;
        case 'last7days':
          endDate = today.toISOString().split('T')[0];
          startDate = new Date(today.setDate(today.getDate() - 7)).toISOString().split('T')[0];
          break;
        case 'last30days':
          endDate = today.toISOString().split('T')[0];
          startDate = new Date(today.setDate(today.getDate() - 30)).toISOString().split('T')[0];
          break;
        case 'thisMonth':
          startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
          endDate = today.toISOString().split('T')[0];
          break;
        case 'lastMonth':
          startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1).toISOString().split('T')[0];
          endDate = new Date(today.getFullYear(), today.getMonth(), 0).toISOString().split('T')[0];
          break;
        case 'custom':
          startDate = document.getElementById('start-date').value;
          endDate = document.getElementById('end-date').value;
          break;
      }

      return { startDate, endDate };
    }

    function loadReport() {
      const reportType = document.getElementById('report-type').value;
      const groupBy = document.getElementById('group-by').value;
      const { startDate, endDate } = getDateRange();

      const reportContent = document.getElementById('report-content');
      reportContent.innerHTML = '<div class="loading">Loading report...</div>';

      $.get('php/admin_crud_api.php', {
        entity: 'reports',
        action: 'generate',
        type: reportType,
        start_date: startDate,
        end_date: endDate,
        group_by: groupBy
      }, function(response) {
        if (response.success) {
          const data = response.data;
          let html = '';

          // Overview Report
          if (reportType === 'overview') {
            html = generateOverviewReport(data);
          }
          // User Analytics Report
          else if (reportType === 'users') {
            html = generateUserReport(data);
          }
          // Revenue Report
          else if (reportType === 'revenue') {
            html = generateRevenueReport(data);
          }
          // Dispute Analysis Report
          else if (reportType === 'disputes') {
            html = generateDisputeReport(data);
          }

          reportContent.innerHTML = html;

          // Initialize charts if any
          if (data.charts) {
            Object.entries(data.charts).forEach(([id, chartData]) => {
              initializeChart(id, chartData);
            });
          }
        } else {
          reportContent.innerHTML = `<div class="error">${response.message}</div>`;
        }
      }).fail(function() {
        reportContent.innerHTML = '<div class="error">Error loading report</div>';
      });
    }

    function generateOverviewReport(data) {
      return `
        <div class="report-grid">
          <div class="report-card">
            <h3>Total Users</h3>
            <div class="report-value">${data.total_users}</div>
            <div class="report-change ${data.user_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.user_change >= 0 ? '↑' : '↓'} ${Math.abs(data.user_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Active Campaigns</h3>
            <div class="report-value">${data.active_campaigns}</div>
            <div class="report-change ${data.campaign_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.campaign_change >= 0 ? '↑' : '↓'} ${Math.abs(data.campaign_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Total Revenue</h3>
            <div class="report-value">$${data.total_revenue.toLocaleString()}</div>
            <div class="report-change ${data.revenue_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.revenue_change >= 0 ? '↑' : '↓'} ${Math.abs(data.revenue_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Open Disputes</h3>
            <div class="report-value">${data.open_disputes}</div>
            <div class="report-change ${data.dispute_change <= 0 ? 'change-positive' : 'change-negative'}">
              ${data.dispute_change <= 0 ? '↓' : '↑'} ${Math.abs(data.dispute_change)}% from previous period
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">User Growth</h3>
            <div class="chart-actions">
              <button class="button button-secondary" onclick="updateChart('user-growth', 'day')">Daily</button>
              <button class="button button-secondary" onclick="updateChart('user-growth', 'week')">Weekly</button>
              <button class="button button-secondary" onclick="updateChart('user-growth', 'month')">Monthly</button>
            </div>
          </div>
          <canvas id="user-growth-chart"></canvas>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">Revenue Trends</h3>
            <div class="chart-actions">
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'day')">Daily</button>
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'week')">Weekly</button>
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'month')">Monthly</button>
            </div>
          </div>
          <canvas id="revenue-trends-chart"></canvas>
        </div>

        <div class="table-container">
          <h3>Recent Activity</h3>
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Activity</th>
                <th>User</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              ${data.recent_activity.map(activity => `
                <tr>
                  <td>${new Date(activity.date).toLocaleString()}</td>
                  <td>${activity.type}</td>
                  <td>${activity.user}</td>
                  <td>${activity.details}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function generateUserReport(data) {
      return `
        <div class="report-grid">
          <div class="report-card">
            <h3>Total Users</h3>
            <div class="report-value">${data.total_users}</div>
            <div class="report-change ${data.user_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.user_change >= 0 ? '↑' : '↓'} ${Math.abs(data.user_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Active Users</h3>
            <div class="report-value">${data.active_users}</div>
            <div class="report-change ${data.active_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.active_change >= 0 ? '↑' : '↓'} ${Math.abs(data.active_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>New Users</h3>
            <div class="report-value">${data.new_users}</div>
            <div class="report-change ${data.new_user_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.new_user_change >= 0 ? '↑' : '↓'} ${Math.abs(data.new_user_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>User Retention</h3>
            <div class="report-value">${data.retention_rate}%</div>
            <div class="report-change ${data.retention_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.retention_change >= 0 ? '↑' : '↓'} ${Math.abs(data.retention_change)}% from previous period
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">User Distribution</h3>
          </div>
          <canvas id="user-distribution-chart"></canvas>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">User Growth by Role</h3>
            <div class="chart-actions">
              <button class="button button-secondary" onclick="updateChart('user-growth-role', 'day')">Daily</button>
              <button class="button button-secondary" onclick="updateChart('user-growth-role', 'week')">Weekly</button>
              <button class="button button-secondary" onclick="updateChart('user-growth-role', 'month')">Monthly</button>
            </div>
          </div>
          <canvas id="user-growth-role-chart"></canvas>
        </div>

        <div class="table-container">
          <h3>User Demographics</h3>
          <table>
            <thead>
              <tr>
                <th>Role</th>
                <th>Total Users</th>
                <th>Active Users</th>
                <th>New Users</th>
                <th>Avg. Activity</th>
              </tr>
            </thead>
            <tbody>
              ${data.demographics.map(demo => `
                <tr>
                  <td>${demo.role}</td>
                  <td>${demo.total_users}</td>
                  <td>${demo.active_users}</td>
                  <td>${demo.new_users}</td>
                  <td>${demo.avg_activity}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function generateRevenueReport(data) {
      return `
        <div class="report-grid">
          <div class="report-card">
            <h3>Total Revenue</h3>
            <div class="report-value">$${data.total_revenue.toLocaleString()}</div>
            <div class="report-change ${data.revenue_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.revenue_change >= 0 ? '↑' : '↓'} ${Math.abs(data.revenue_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Active Campaigns</h3>
            <div class="report-value">${data.active_campaigns}</div>
            <div class="report-change ${data.campaign_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.campaign_change >= 0 ? '↑' : '↓'} ${Math.abs(data.campaign_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Avg. Campaign Value</h3>
            <div class="report-value">$${data.avg_campaign_value.toLocaleString()}</div>
            <div class="report-change ${data.avg_value_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.avg_value_change >= 0 ? '↑' : '↓'} ${Math.abs(data.avg_value_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Platform Fee</h3>
            <div class="report-value">$${data.platform_fee.toLocaleString()}</div>
            <div class="report-change ${data.fee_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.fee_change >= 0 ? '↑' : '↓'} ${Math.abs(data.fee_change)}% from previous period
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">Revenue Trends</h3>
            <div class="chart-actions">
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'day')">Daily</button>
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'week')">Weekly</button>
              <button class="button button-secondary" onclick="updateChart('revenue-trends', 'month')">Monthly</button>
            </div>
          </div>
          <canvas id="revenue-trends-chart"></canvas>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">Revenue by Category</h3>
          </div>
          <canvas id="revenue-category-chart"></canvas>
        </div>

        <div class="table-container">
          <h3>Top Campaigns</h3>
          <table>
            <thead>
              <tr>
                <th>Campaign</th>
                <th>Business</th>
                <th>Budget</th>
                <th>Revenue</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              ${data.top_campaigns.map(campaign => `
                <tr>
                  <td>${campaign.title}</td>
                  <td>${campaign.business}</td>
                  <td>$${campaign.budget.toLocaleString()}</td>
                  <td>$${campaign.revenue.toLocaleString()}</td>
                  <td>${campaign.status}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function generateDisputeReport(data) {
      return `
        <div class="report-grid">
          <div class="report-card">
            <h3>Total Disputes</h3>
            <div class="report-value">${data.total_disputes}</div>
            <div class="report-change ${data.dispute_change <= 0 ? 'change-positive' : 'change-negative'}">
              ${data.dispute_change <= 0 ? '↓' : '↑'} ${Math.abs(data.dispute_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Open Disputes</h3>
            <div class="report-value">${data.open_disputes}</div>
            <div class="report-change ${data.open_change <= 0 ? 'change-positive' : 'change-negative'}">
              ${data.open_change <= 0 ? '↓' : '↑'} ${Math.abs(data.open_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Resolution Rate</h3>
            <div class="report-value">${data.resolution_rate}%</div>
            <div class="report-change ${data.resolution_change >= 0 ? 'change-positive' : 'change-negative'}">
              ${data.resolution_change >= 0 ? '↑' : '↓'} ${Math.abs(data.resolution_change)}% from previous period
            </div>
          </div>
          <div class="report-card">
            <h3>Avg. Resolution Time</h3>
            <div class="report-value">${data.avg_resolution_time}</div>
            <div class="report-change ${data.resolution_time_change <= 0 ? 'change-positive' : 'change-negative'}">
              ${data.resolution_time_change <= 0 ? '↓' : '↑'} ${Math.abs(data.resolution_time_change)}% from previous period
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">Dispute Trends</h3>
            <div class="chart-actions">
              <button class="button button-secondary" onclick="updateChart('dispute-trends', 'day')">Daily</button>
              <button class="button button-secondary" onclick="updateChart('dispute-trends', 'week')">Weekly</button>
              <button class="button button-secondary" onclick="updateChart('dispute-trends', 'month')">Monthly</button>
            </div>
          </div>
          <canvas id="dispute-trends-chart"></canvas>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <h3 class="chart-title">Dispute Types</h3>
          </div>
          <canvas id="dispute-types-chart"></canvas>
        </div>

        <div class="table-container">
          <h3>Recent Disputes</h3>
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Campaign</th>
                <th>Type</th>
                <th>Status</th>
                <th>Resolution Time</th>
              </tr>
            </thead>
            <tbody>
              ${data.recent_disputes.map(dispute => `
                <tr>
                  <td>${new Date(dispute.date).toLocaleString()}</td>
                  <td>${dispute.campaign}</td>
                  <td>${dispute.type}</td>
                  <td>${dispute.status}</td>
                  <td>${dispute.resolution_time}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      `;
    }

    function initializeChart(id, data) {
      const ctx = document.getElementById(`${id}-chart`).getContext('2d');
      new Chart(ctx, {
        type: data.type,
        data: data.data,
        options: data.options
      });
    }

    function updateChart(chartId, period) {
      const reportType = document.getElementById('report-type').value;
      const { startDate, endDate } = getDateRange();

      $.get('php/admin_crud_api.php', {
        entity: 'reports',
        action: 'update_chart',
        type: reportType,
        chart: chartId,
        period: period,
        start_date: startDate,
        end_date: endDate
      }, function(response) {
        if (response.success) {
          const chart = Chart.getChart(`${chartId}-chart`);
          if (chart) {
            chart.data = response.data.data;
            chart.options = response.data.options;
            chart.update();
          }
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error updating chart', 'error');
      });
    }

    function exportReport(format) {
      const reportType = document.getElementById('report-type').value;
      const { startDate, endDate } = getDateRange();
      const groupBy = document.getElementById('group-by').value;

      window.location.href = `php/admin_crud_api.php?entity=reports&action=export&type=${reportType}&format=${format}&start_date=${startDate}&end_date=${endDate}&group_by=${groupBy}`;
    }

    // Initialize date range picker
    document.getElementById('date-range').addEventListener('change', updateDateInputs);

    // Load initial report
    $(document).ready(function() {
      loadReport();
    });
  </script>
</body>

</html> 