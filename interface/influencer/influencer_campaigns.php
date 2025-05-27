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
  <title>Browse Campaigns - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <link rel="stylesheet" href="../../assets/css/form-controls.css" />
  <style>
    .campaigns-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .campaign-card {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: var(--shadow-sm);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .campaign-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .campaign-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }

    .campaign-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .campaign-category {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      background: var(--bg-light);
      border-radius: 1rem;
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .campaign-budget {
      font-size: 1.125rem;
      font-weight: 600;
      color: var(--primary);
    }

    .campaign-description {
      color: var(--text-secondary);
      font-size: 0.875rem;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .campaign-details {
      margin-bottom: 1rem;
    }

    .campaign-detail {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .campaign-detail svg {
      width: 1rem;
      height: 1rem;
      color: var(--text-primary);
    }

    .campaign-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .campaign-tag {
      padding: 0.25rem 0.75rem;
      background: var(--bg-light);
      border-radius: 1rem;
      font-size: 0.75rem;
      color: var(--text-secondary);
    }

    .campaign-actions {
      display: flex;
      gap: 0.5rem;
    }

    .campaign-actions .button {
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

    .form-group {
      margin-bottom: 0;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.2s ease;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 1rem;
      padding-right: 2.5rem;
    }

    .form-select:hover {
      border-color: var(--primary);
    }

    .form-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    .search-bar {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .search-bar input {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 2.5rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .search-bar input:hover {
      border-color: var(--primary);
    }

    .search-bar input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    .search-bar svg {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      width: 1rem;
      height: 1rem;
      color: var(--text-secondary);
      pointer-events: none;
    }

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

    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 12px 24px;
      border-radius: 4px;
      color: white;
      font-size: 14px;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-success {
      background-color: var(--success);
    }

    .toast-error {
      background-color: var(--danger);
    }
  </style>
</head>

<body>
<nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link ">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link active">Campaigns</a>
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
      <!-- Search and Filters -->
      <div class="filters">
        <div class="search-bar">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input type="text" placeholder="Search campaigns by title, description, or brand..." onkeyup="filterCampaigns(this.value)" />
        </div>
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Category</label>
            <select class="form-select" onchange="updateFilters(this)">
              <option value="">All Categories</option>
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
            <label class="form-label">Budget Range</label>
            <select class="form-select" onchange="updateFilters(this)">
              <option value="">Any Budget</option>
              <option value="0-1000">$0 - $1,000</option>
              <option value="1000-5000">$1,000 - $5,000</option>
              <option value="5000-10000">$5,000 - $10,000</option>
              <option value="10000+">$10,000+</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Platform</label>
            <select class="form-select" onchange="updateFilters(this)">
              <option value="">All Platforms</option>
              <option value="instagram">Instagram</option>
              <option value="tiktok">TikTok</option>
              <option value="youtube">YouTube</option>
              <option value="twitter">Twitter</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sort By</label>
            <select class="form-select" onchange="updateFilters(this)">
              <option value="newest">Newest First</option>
              <option value="budget-high">Highest Budget</option>
              <option value="budget-low">Lowest Budget</option>
              <option value="deadline">Application Deadline</option>
            </select>
          </div>
        </div>
        <div class="active-filters" id="activeFilters">
          <!-- Active filters will be added here dynamically -->
        </div>
      </div>

      <!-- Campaigns Grid -->
      <div class="campaigns-grid" id="campaignsGrid">
        <!-- Sample Campaign Cards (Replace with dynamic data) -->
        <div class="campaign-card">
          <div class="campaign-header">
            <div>
              <h3 class="campaign-title">Summer Collection Launch</h3>
              <span class="campaign-category">Fashion</span>
            </div>
            <div class="campaign-budget">$5,000 - $10,000</div>
          </div>
          <p class="campaign-description">
            Looking for fashion influencers to promote our new summer collection. 
            The campaign will focus on showcasing our latest designs through lifestyle 
            and fashion content.
          </p>
          <div class="campaign-details">
            <div class="campaign-detail">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Application Deadline: June 1, 2024</span>
            </div>
            <div class="campaign-detail">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span>Target Audience: 18-35, Fashion Enthusiasts</span>
            </div>
          </div>
          <div class="campaign-tags">
            <span class="campaign-tag">Instagram</span>
            <span class="campaign-tag">TikTok</span>
            <span class="campaign-tag">10k+ Followers</span>
          </div>
          <div class="campaign-actions">
            <button class="button" onclick="viewCampaignDetails(1)">View Details</button>
            <button class="button button-primary" onclick="applyToCampaign(1)">Apply Now</button>
          </div>
        </div>

        <!-- Add more campaign cards here -->
      </div>
    </div>
  </main>

  <script>
    // Filter Campaigns
    function filterCampaigns() {
      // Implement filtering logic here
      // This is a placeholder for the actual filtering implementation
      console.log('Filtering campaigns...');
    }

    // View Campaign Details
    function viewCampaignDetails(campaignId) {
      // Implement campaign details view
      // This could open a modal or navigate to a details page
      console.log('Viewing campaign details:', campaignId);
    }

    // Apply to Campaign
    function applyToCampaign(campaignId) {
      // Implement application logic
      // This could open an application form or modal
      showToast('Application submitted successfully');
    }

    // Toast Notifications
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

    // Filter Management
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
      filterCampaigns();
    }

    function updateActiveFiltersDisplay() {
      const container = document.getElementById('activeFilters');
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
      document.querySelectorAll('.filters select').forEach(select => {
        select.value = '';
      });
      activeFilters.clear();
      updateActiveFiltersDisplay();
      filterCampaigns();
    }

    // Initialize filters
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.filters select').forEach(select => {
        select.addEventListener('change', () => updateFilters(select));
      });
      updateActiveFiltersDisplay();
    });
  </script>
</body>

</html> 