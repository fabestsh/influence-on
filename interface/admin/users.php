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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
      max-width: 600px;
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
          <button class="button button-primary" onclick="openCreateUserModal()">Add New User</button>
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

  <!-- Create/Edit User Modal -->
  <div id="user-modal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeUserModal()">&times;</span>
      <h2 id="modal-title">Add New User</h2>
      <form id="user-form" onsubmit="handleUserSubmit(event)">
        <input type="hidden" id="user-id">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password">
          <small>Leave blank to keep existing password when editing</small>
        </div>
        <div class="form-group">
          <label for="role">Role</label>
          <select id="role" name="role" required onchange="toggleRoleFields()">
            <option value="admin">Admin</option>
            <option value="business">Business</option>
            <option value="influencer">Influencer</option>
          </select>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <!-- Business-specific fields -->
        <div id="business-fields" style="display: none;">
          <div class="form-group">
            <label for="business_name">Business Name</label>
            <input type="text" id="business_name" name="business_name">
          </div>
          <div class="form-group">
            <label for="industry">Industry</label>
            <input type="text" id="industry" name="industry">
          </div>
          <div class="form-group">
            <label for="website">Website</label>
            <input type="url" id="website" name="website">
          </div>
          <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" id="contact_info" name="contact_info">
          </div>
        </div>

        <!-- Influencer-specific fields -->
        <div id="influencer-fields" style="display: none;">
          <div class="form-group">
            <label for="social_links">Social Links (JSON)</label>
            <textarea id="social_links" name="social_links" placeholder='{"instagram": "https://instagram.com/...", "twitter": "https://twitter.com/..."}'></textarea>
          </div>
          <div class="form-group">
            <label for="expertise">Expertise (JSON)</label>
            <textarea id="expertise" name="expertise" placeholder='["fashion", "lifestyle", "travel"]'></textarea>
          </div>
          <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="13" max="100">
          </div>
          <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio"></textarea>
          </div>
        </div>

        <button type="submit" class="button button-primary">Save User</button>
      </form>
    </div>
  </div>

  <script>
    let currentPage = 1;
    const perPage = 10;

    function showMessage(message, type) {
      const messageDiv = document.getElementById('message');
      messageDiv.className = type;
      messageDiv.textContent = message;
      setTimeout(() => {
        messageDiv.textContent = '';
        messageDiv.className = '';
      }, 5000);
    }

    function loadUsers(page = 1) {
      currentPage = page;
      const search = document.querySelector('.search-input').value;
      const role = document.querySelector('.filter-select').value;
      const status = document.querySelector('.filter-select').value;

      const usersList = document.getElementById('users-list');
      usersList.innerHTML = '<div class="loading">Loading users...</div>';

      $.get('php/admin_crud_api.php', {
        entity: 'users',
        action: 'list',
        page: page,
        per_page: perPage,
        search: search,
        role: role,
        status: status
      }, function(response) {
        if (response.success) {
          const { data, total_pages } = response.data;
          let html = '<div class="list">';
          
          if (data.length === 0) {
            html = '<div class="empty-state">No users found</div>';
          } else {
            data.forEach(user => {
              html += `
                <div class="list-item">
                  <div class="list-item-content">
                    <div class="list-item-title">${user.name}</div>
                    <div class="list-item-subtitle">
                      ${user.email} - ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                      ${user.profile_info ? `(${user.profile_info})` : ''}
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <button class="button button-primary" onclick="editUser(${user.id})">Edit</button>
                    <button class="button button-danger" onclick="deleteUser(${user.id})">Delete</button>
                  </div>
                </div>
              `;
            });
          }
          
          html += '</div>';
          usersList.innerHTML = html;

          // Update pagination
          let paginationHtml = '';
          for (let i = 1; i <= total_pages; i++) {
            paginationHtml += `
              <button class="${i === page ? 'active' : ''}" 
                      onclick="loadUsers(${i})">${i}</button>
            `;
          }
          document.getElementById('pagination').innerHTML = paginationHtml;
        } else {
          usersList.innerHTML = `<div class="error">${response.message}</div>`;
        }
      }).fail(function() {
        usersList.innerHTML = '<div class="error">Error loading users</div>';
      });
    }

    function openCreateUserModal() {
      document.getElementById('modal-title').textContent = 'Add New User';
      document.getElementById('user-form').reset();
      document.getElementById('user-id').value = '';
      document.getElementById('password').required = true;
      toggleRoleFields();
      document.getElementById('user-modal').style.display = 'block';
    }

    function closeUserModal() {
      document.getElementById('user-modal').style.display = 'none';
    }

    function toggleRoleFields() {
      const role = document.getElementById('role').value;
      document.getElementById('business-fields').style.display = role === 'business' ? 'block' : 'none';
      document.getElementById('influencer-fields').style.display = role === 'influencer' ? 'block' : 'none';
    }

    function editUser(userId) {
      $.get('php/admin_crud_api.php', {
        entity: 'users',
        action: 'get',
        id: userId
      }, function(response) {
        if (response.success) {
          const user = response.data;
          document.getElementById('modal-title').textContent = 'Edit User';
          document.getElementById('user-id').value = user.id;
          document.getElementById('name').value = user.name;
          document.getElementById('email').value = user.email;
          document.getElementById('role').value = user.role;
          document.getElementById('status').value = user.status;
          document.getElementById('password').required = false;

          // Set role-specific fields
          if (user.role === 'business') {
            const profile = JSON.parse(user.profile_info || '{}');
            document.getElementById('business_name').value = profile.name || '';
            document.getElementById('industry').value = profile.industry || '';
            document.getElementById('website').value = profile.website || '';
            document.getElementById('contact_info').value = profile.contact_info || '';
          } else if (user.role === 'influencer') {
            const profile = JSON.parse(user.profile_info || '{}');
            document.getElementById('social_links').value = JSON.stringify(profile.social_links || {}, null, 2);
            document.getElementById('expertise').value = JSON.stringify(profile.expertise || [], null, 2);
            document.getElementById('age').value = profile.age || '';
            document.getElementById('bio').value = profile.bio || '';
          }

          toggleRoleFields();
          document.getElementById('user-modal').style.display = 'block';
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error loading user data', 'error');
      });
    }

    function handleUserSubmit(event) {
      event.preventDefault();
      const form = event.target;
      const userId = document.getElementById('user-id').value;
      const formData = new FormData(form);
      
      // Convert JSON fields
      if (formData.get('role') === 'influencer') {
        try {
          formData.set('social_links', JSON.parse(formData.get('social_links')));
          formData.set('expertise', JSON.parse(formData.get('expertise')));
        } catch (e) {
          showMessage('Invalid JSON in social links or expertise', 'error');
          return;
        }
      }

      const action = userId ? 'update' : 'create';
      if (action === 'update') {
        formData.append('id', userId);
      }

      $.ajax({
        url: `php/admin_crud_api.php?entity=users&action=${action}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            showMessage(`User ${action === 'create' ? 'created' : 'updated'} successfully`, 'success');
            closeUserModal();
            loadUsers(currentPage);
          } else {
            showMessage(response.message, 'error');
          }
        },
        error: function() {
          showMessage(`Error ${action === 'create' ? 'creating' : 'updating'} user`, 'error');
        }
      });
    }

    function deleteUser(userId) {
      if (!confirm('Are you sure you want to delete this user?')) return;

      $.post('php/admin_crud_api.php', {
        entity: 'users',
        action: 'delete',
        id: userId
      }, function(response) {
        if (response.success) {
          showMessage('User deleted successfully', 'success');
          loadUsers(currentPage);
        } else {
          showMessage(response.message, 'error');
        }
      }).fail(function() {
        showMessage('Error deleting user', 'error');
      });
    }

    // Load users when the page loads
    $(document).ready(function() {
      loadUsers();
    });
  </script>
</body>

</html> 