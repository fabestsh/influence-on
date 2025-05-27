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
  <title>Influencers - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <link rel="stylesheet" href="../../assets/css/form-controls.css" />
  <style>
    .influencers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .influencer-card {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: var(--shadow-sm);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .influencer-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .influencer-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .influencer-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      object-fit: cover;
    }

    .influencer-info {
      flex: 1;
    }

    .influencer-name {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .influencer-category {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .influencer-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-bottom: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: 0.5rem;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .stat-label {
      font-size: 0.75rem;
      color: var(--text-secondary);
    }

    .influencer-expertise {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .expertise-tag {
      padding: 0.25rem 0.75rem;
      background: var(--bg-light);
      border-radius: 1rem;
      font-size: 0.75rem;
      color: var(--text-secondary);
    }

    .influencer-actions {
      display: flex;
      gap: 0.5rem;
    }

    .influencer-actions .button {
      flex: 1;
    }

    .filters {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
    }

    .filters-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    /* Modal Styles */
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

    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 1.5rem;
      color: var(--text-primary);
    }

    .close-button {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.5rem;
    }

    .close-button:hover {
      color: var(--text-primary);
    }

    /* Toast Styles */
    .toast {
      position: fixed;
      bottom: 1rem;
      right: 1rem;
      padding: 1rem 1.5rem;
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      box-shadow: var(--shadow-md);
      transform: translateY(100%);
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-success {
      border-left: 4px solid var(--success);
    }

    .toast-error {
      border-left: 4px solid var(--danger);
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencers.php" class="nav-link active">Influencers</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
        <a href="../chat/chat.php" class="nav-link">Messages</a>
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
          <h1 class="welcome-text">Influencer Management</h1>
          <p class="text-secondary">Discover and manage influencer relationships</p>
        </div>
        <div class="flex gap-2">
          <button class="button" onclick="openModal('filterModal')">Filter Influencers</button>
        </div>
      </div>

      <!-- Filters -->
      <div class="filters">
        <div class="search-bar">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input type="text" id="searchInput" placeholder="Search influencers by name, expertise, or bio..." onkeyup="updateFilters()">
        </div>
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Expertise</label>
            <select class="form-select" id="expertiseFilter" onchange="updateFilters()">
              <option value="">All Expertise</option>
              <option value="fashion">Fashion</option>
              <option value="beauty">Beauty</option>
              <option value="lifestyle">Lifestyle</option>
              <option value="tech">Technology</option>
              <option value="food">Food & Dining</option>
              <option value="travel">Travel</option>
              <option value="fitness">Fitness & Health</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sort By</label>
            <select class="form-select" id="sortFilter" onchange="updateFilters()">
              <option value="relevance">Relevance</option>
              <option value="collaborations">Most Collaborations</option>
              <option value="earnings">Highest Earnings</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Influencers Grid -->
      <div class="influencers-grid" id="influencersGrid">
        <!-- Influencer cards will be dynamically added here -->
      </div>
    </div>
  </main>

  <!-- Influencer Details Modal -->
  <div id="influencerDetailsModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Influencer Details</h2>
        <button class="close-button" onclick="closeModal('influencerDetailsModal')">&times;</button>
      </div>
      <div id="influencerDetailsContent">
        <!-- Details will be dynamically added here -->
      </div>
    </div>
  </div>

  <script>
    // Influencer Management Functions
    let influencers = [];
    let currentFilters = {
      search: '',
      expertise: '',
      sort: 'relevance'
    };

    // Load influencers
    async function loadInfluencers() {
      try {
        const queryParams = new URLSearchParams(currentFilters);
        const response = await fetch(`php/business_api.php?endpoint=influencers&${queryParams}`);
        const result = await response.json();
        
        if (result.success) {
          influencers = result.data;
          renderInfluencers();
        } else {
          showToast('Failed to load influencers', 'error');
        }
      } catch (error) {
        console.error('Error loading influencers:', error);
        showToast('Error loading influencers', 'error');
      }
    }

    // Render influencers in the grid
    function renderInfluencers() {
      const grid = document.getElementById('influencersGrid');
      if (!grid) return;

      grid.innerHTML = influencers.map(influencer => `
        <div class="influencer-card" data-id="${influencer.id}">
          <div class="influencer-header">
            <img src="${influencer.avatar || 'https://i.pravatar.cc/150?img=' + influencer.id}" 
                 alt="${influencer.name}" 
                 class="influencer-avatar">
            <div class="influencer-info">
              <h3 class="influencer-name">${influencer.name}</h3>
              <div class="influencer-category">
                ${Object.values(JSON.parse(influencer.expertise || '[]')).join(', ')}
              </div>
            </div>
          </div>
          <div class="influencer-stats">
            <div class="stat-item">
              <div class="stat-value">${influencer.total_collaborations || 0}</div>
              <div class="stat-label">Collaborations</div>
            </div>
            <div class="stat-item">
              <div class="stat-value">$${parseFloat(influencer.total_earnings || 0).toLocaleString()}</div>
              <div class="stat-label">Total Earnings</div>
            </div>
          </div>
          <div class="influencer-expertise">
            ${Object.values(JSON.parse(influencer.expertise || '[]')).map(expertise => 
              `<span class="expertise-tag">${expertise}</span>`
            ).join('')}
          </div>
          <div class="influencer-actions">
            <button class="button" onclick="viewInfluencerDetails(${influencer.id})">View Details</button>
            <button class="button button-primary" onclick="startChat(${influencer.id})">Message</button>
          </div>
        </div>
      `).join('');
    }

    // View influencer details
    async function viewInfluencerDetails(influencerId) {
      try {
        const response = await fetch(`php/business_api.php?endpoint=influencers&id=${influencerId}`);
        const result = await response.json();
        
        if (result.success) {
          const influencer = result.data;
          const content = document.getElementById('influencerDetailsContent');
          
          content.innerHTML = `
            <div class="influencer-header">
              <img src="${influencer.avatar || 'https://i.pravatar.cc/150?img=' + influencer.id}" 
                   alt="${influencer.name}" 
                   class="influencer-avatar">
              <div class="influencer-info">
                <h3 class="influencer-name">${influencer.name}</h3>
                <div class="influencer-category">${influencer.email}</div>
              </div>
            </div>
            <div class="influencer-stats">
              <div class="stat-item">
                <div class="stat-value">${influencer.total_collaborations || 0}</div>
                <div class="stat-label">Total Collaborations</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">$${parseFloat(influencer.total_earnings || 0).toLocaleString()}</div>
                <div class="stat-label">Total Earnings</div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Bio</label>
              <p>${influencer.bio || 'No bio available'}</p>
            </div>
            <div class="form-group">
              <label class="form-label">Expertise</label>
              <div class="influencer-expertise">
                ${Object.values(JSON.parse(influencer.expertise || '[]')).map(expertise => 
                  `<span class="expertise-tag">${expertise}</span>`
                ).join('')}
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Social Links</label>
              <div class="social-links">
                ${Object.entries(JSON.parse(influencer.social_links || '{}')).map(([platform, link]) => 
                  `<a href="${link}" target="_blank" class="social-link">${platform}</a>`
                ).join('')}
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Campaign History</label>
              <p>${influencer.campaign_history || 'No campaign history available'}</p>
            </div>
            <div class="button-group">
              <button class="button" onclick="closeModal('influencerDetailsModal')">Close</button>
              <button class="button button-primary" onclick="startChat(${influencer.id})">Start Chat</button>
            </div>
          `;
          
          openModal('influencerDetailsModal');
        } else {
          showToast('Failed to load influencer details', 'error');
        }
      } catch (error) {
        console.error('Error loading influencer details:', error);
        showToast('Error loading influencer details', 'error');
      }
    }

    // Start chat with influencer
    function startChat(influencerId) {
      window.location.href = `../chat/chat.php?influencer_id=${influencerId}`;
    }

    // Update filters
    function updateFilters() {
      const searchInput = document.getElementById('searchInput');
      const expertiseFilter = document.getElementById('expertiseFilter');
      const sortFilter = document.getElementById('sortFilter');
      
      currentFilters = {
        search: searchInput.value,
        expertise: expertiseFilter.value,
        sort: sortFilter.value
      };
      
      loadInfluencers();
    }

    // Modal management
    function openModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add('show');
      }
    }

    function closeModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.remove('show');
      }
    }

    // Toast notifications
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);
      
      setTimeout(() => toast.classList.add('show'), 100);
      
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      loadInfluencers();
      
      // Add event listeners for filters
      const searchInput = document.getElementById('searchInput');
      const expertiseFilter = document.getElementById('expertiseFilter');
      const sortFilter = document.getElementById('sortFilter');
      
      if (searchInput) {
        searchInput.addEventListener('input', debounce(updateFilters, 300));
      }
      
      if (expertiseFilter) {
        expertiseFilter.addEventListener('change', updateFilters);
      }
      
      if (sortFilter) {
        sortFilter.addEventListener('change', updateFilters);
      }
    });

    // Utility function for debouncing
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }
  </script>
</body>
</html>