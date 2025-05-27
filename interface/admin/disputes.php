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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }

    .modal-content {
      position: relative;
      background: white;
      margin: 10% auto;
      padding: 20px;
      width: 80%;
      max-width: 800px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .close {
      position: absolute;
      right: 20px;
      top: 10px;
      font-size: 24px;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .form-group textarea {
      min-height: 100px;
    }

    .pagination {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin: 1rem 0;
    }

    .pagination button {
      padding: 0.5rem 1rem;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
    }

    .pagination button.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }

    .filters input,
    .filters select {
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .loading {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }

    .error {
      color: var(--danger);
      margin-bottom: 1rem;
    }

    .success {
      color: var(--success);
      margin-bottom: 1rem;
    }

    .dispute-status {
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .status-open {
      background: var(--warning-light);
      color: var(--warning);
    }

    .status-resolved {
      background: var(--success-light);
      color: var(--success);
    }

    .status-closed {
      background: var(--danger-light);
      color: var(--danger);
    }

    .dispute-details {
      margin-top: 1rem;
      padding: 1rem;
      background: var(--background-light);
      border-radius: 4px;
    }

    .dispute-timeline {
      margin-top: 1rem;
      padding-left: 1rem;
      border-left: 2px solid var(--border);
    }

    .timeline-item {
      position: relative;
      padding-bottom: 1rem;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: -1.5rem;
      top: 0.25rem;
      width: 1rem;
      height: 1rem;
      border-radius: 50%;
      background: var(--primary);
    }

    .timeline-date {
      font-size: 0.875rem;
      color: var(--text-secondary);
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
        <form method="POST" action="../../interface/auth/php/logout.php">
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
          <button class="button button-primary" onclick="openCreateDisputeModal()">Create Dispute</button>
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

  <!-- Create/Edit Dispute Modal -->
  <div id="dispute-modal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeDisputeModal()">&times;</span>
      <h2 id="modal-title">Create Dispute</h2>
      <form id="dispute-form" onsubmit="handleDisputeSubmit(event)">
        <input type="hidden" id="dispute-id">
        <div class="form-group">
          <label for="campaign_id">Campaign</label>
          <select id="campaign_id" name="campaign_id" required>
            <option value="">Select Campaign</option>
          </select>
        </div>
        <div class="form-group">
          <label for="type">Type</label>
          <select id="type" name="type" required>
            <option value="payment">Payment</option>
            <option value="content">Content</option>
            <option value="delivery">Delivery</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" required>
            <option value="open">Open</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
        </div>
        <div class="form-group">
          <label for="resolution">Resolution</label>
          <textarea id="resolution" name="resolution"></textarea>
        </div>
        <button type="submit" class="button button-primary">Save Dispute</button>
      </form>
    </div>
  </div>

  <!-- View Dispute Modal -->
  <div id="view-dispute-modal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeViewDisputeModal()">&times;</span>
      <h2>Dispute Details</h2>
      <div id="dispute-details"></div>
      <div class="dispute-timeline" id="dispute-timeline"></div>
      <div class="flex gap-2 mt-4">
        <button class="button button-primary" onclick="editCurrentDispute()">Edit</button>
        <button class="button button-danger" onclick="deleteCurrentDispute()">Delete</button>
      </div>
    </div>
  </div>

  <script>
    function toggleDispute(header) {
      const content = header.nextElementSibling;
      content.classList.toggle('active');
    }

    let currentPage = 1;
    const perPage = 10;
    let currentDisputeId = null;

    function showMessage(message, type) {
      const messageDiv = document.getElementById('message');
      messageDiv.className = type;
      messageDiv.textContent = message;
      setTimeout(() => {
        messageDiv.textContent = '';
        messageDiv.className = '';
      }, 5000);
    }

    function loadDisputes(page = 1) {
      currentPage = page;
      const search = document.querySelector('.search-input').value;
      const status = document.querySelector('.filter-select').value;
      const type = document.querySelector('.filter-select').value;

      const disputesList = document.getElementById('disputes-list');
      disputesList.innerHTML = '<div class="loading">Loading disputes...</div>';

      $.get('php/admin_crud_api.php', {
        entity: 'disputes',
        action: 'list',
        page: page,
        per_page: perPage,
        search: search,
        status: status,
        type: type
      }, function(response) {
        if (response.success) {
          const { data, total_pages } = response.data;
          let html = '<div class="list">';
          
          if (data.length === 0) {
            html = '<div class="empty-state">No disputes found</div>';
          } else {
            data.forEach(dispute => {
              html += `
                <div class="list-item">
                  <div class="list-item-content">
                    <div class="list-item-title">
                      ${dispute.campaign_title}
                      <span class="dispute-status status-${dispute.status}">${dispute.status}</span>
                    </div>
                    <div class="list-item-subtitle">
                      ${dispute.type.charAt(0).toUpperCase() + dispute.type.slice(1)} - 
                      Reported by ${dispute.reporter_name}
                    </div>
                    <div class="list-item-description">${dispute.description}</div>
                  </div>
                  <div class="flex gap-2">
                    <button class="button button-primary" onclick="viewDispute(${dispute.id})">View</button>
                    <button class="button button-primary" onclick="editDispute(${dispute.id})">Edit</button>
                    <button class="button button-danger" onclick="deleteDispute(${dispute.id})">Delete</button>
                  </div>
                </div>
              `;
            });
          }
          
          html += '</div>';
          disputesList.innerHTML = html;

          // Update pagination
          let paginationHtml = '';
          for (let i = 1; i <= total_pages; i++) {
            paginationHtml += `
              <button class="${i === page ? 'active' : ''}" 
                      onclick="loadDisputes(${i})">${i}</button>
            `;
          }
          document.getElementById('pagination').innerHTML = paginationHtml;
        } else {
          disputesList.innerHTML = `<div class="error">${response.message}</div>`;
        }
      }).fail(function() {
        disputesList.innerHTML = '<div class="error">Error loading disputes</div>';
      });
    }

    function loadCampaigns() {
      $.get('php/admin_crud_api.php', {
        entity: 'campaigns',
        action: 'list',
        status: 'active'
      }, function(response) {
        if (response.success) {
          const select = document.getElementById('campaign_id');
          select.innerHTML = '<option value="">Select Campaign</option>';
          response.data.forEach(campaign => {
            select.innerHTML += `
              <option value="${campaign.id}">${campaign.title}</option>
            `;
          });
        }
      });
    }

    function openCreateDisputeModal() {
      document.getElementById('modal-title').textContent = 'Create Dispute';
      document.getElementById('dispute-form').reset();
      document.getElementById('dispute-id').value = '';
      document.getElementById('status').value = 'open';
      document.getElementById('resolution').value = '';
      loadCampaigns();
      document.getElementById('dispute-modal').style.display = 'block';
    }

    function closeDisputeModal() {
      document.getElementById('dispute-modal').style.display = 'none';
    }

    function closeViewDisputeModal() {
      document.getElementById('view-dispute-modal').style.display = 'none';
      currentDisputeId = null;
    }

    function viewDispute(disputeId) {
      currentDisputeId = disputeId;
      $.get('php/admin_crud_api.php', {
        entity: 'disputes',
        action: 'get',
        id: disputeId
      }, function(response) {
        if (response.success) {
          const dispute = response.data;
          const details = document.getElementById('dispute-details');
          const timeline = document.getElementById('dispute-timeline');

          details.innerHTML = `
            <div class="dispute-details">
              <h3>${dispute.campaign_title}</h3>
              <p><strong>Type:</strong> ${dispute.type}</p>
              <p><strong>Status:</strong> <span class="dispute-status status-${dispute.status}">${dispute.status}</span></p>
              <p><strong>Reported by:</strong> ${dispute.reporter_name}</p>
              <p><strong>Description:</strong></p>
              <p>${dispute.description}</p>
              ${dispute.resolution ? `
                <p><strong>Resolution:</strong></p>
                <p>${dispute.resolution}</p>
              ` : ''}
            </div>
          `;

          timeline.innerHTML = `
            <div class="timeline-item">
              <div class="timeline-date">${new Date(dispute.created_at).toLocaleString()}</div>
              <div>Dispute created</div>
            </div>
            ${dispute.resolved_at ? `
              <div class="timeline-item">
                <div class="timeline-date">${new Date(dispute.resolved_at).toLocaleString()}</div>
                <div>Dispute ${dispute.status}</div>
              </div>
            ` : ''}
          `;

          document.getElementById('view-dispute-modal').style.display = 'block';
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error loading dispute details', 'error');
      });
    }

    function editDispute(disputeId) {
      currentDisputeId = disputeId;
      $.get('php/admin_crud_api.php', {
        entity: 'disputes',
        action: 'get',
        id: disputeId
      }, function(response) {
        if (response.success) {
          const dispute = response.data;
          document.getElementById('modal-title').textContent = 'Edit Dispute';
          document.getElementById('dispute-id').value = dispute.id;
          document.getElementById('campaign_id').value = dispute.campaign_id;
          document.getElementById('type').value = dispute.type;
          document.getElementById('description').value = dispute.description;
          document.getElementById('status').value = dispute.status;
          document.getElementById('resolution').value = dispute.resolution || '';
          loadCampaigns();
          document.getElementById('dispute-modal').style.display = 'block';
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error loading dispute data', 'error');
      });
    }

    function editCurrentDispute() {
      if (currentDisputeId) {
        closeViewDisputeModal();
        editDispute(currentDisputeId);
      }
    }

    function deleteCurrentDispute() {
      if (currentDisputeId) {
        closeViewDisputeModal();
        deleteDispute(currentDisputeId);
      }
    }

    function handleDisputeSubmit(event) {
      event.preventDefault();
      const form = event.target;
      const disputeId = document.getElementById('dispute-id').value;
      const formData = new FormData(form);

      const action = disputeId ? 'update' : 'create';
      if (action === 'update') {
        formData.append('id', disputeId);
      }

      $.ajax({
        url: `php/admin_crud_api.php?entity=disputes&action=${action}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            showMessage(`Dispute ${action === 'create' ? 'created' : 'updated'} successfully`, 'success');
            closeDisputeModal();
            loadDisputes(currentPage);
          } else {
            showMessage(response.message, 'error');
          }
        },
        error: function() {
          showMessage(`Error ${action === 'create' ? 'creating' : 'updating'} dispute`, 'error');
        }
      });
    }

    function deleteDispute(disputeId) {
      if (!confirm('Are you sure you want to delete this dispute?')) return;

      $.post('php/admin_crud_api.php', {
        entity: 'disputes',
        action: 'delete',
        id: disputeId
      }, function(response) {
        if (response.success) {
          showMessage('Dispute deleted successfully', 'success');
          loadDisputes(currentPage);
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error deleting dispute', 'error');
      });
    }

    // Load disputes when the page loads
    $(document).ready(function() {
      loadDisputes();
    });
  </script>
</body>

</html> 