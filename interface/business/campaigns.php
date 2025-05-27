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
  <title>Campaigns - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <style>
    /* Select Dropdown Styling */
    select.input {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-color: var(--bg-white);
      border: 1px solid rgba(99, 102, 241, 0.2);
      border-radius: 0.5rem;
      padding: 0.625rem 2.5rem 0.625rem 1rem;
      font-size: 0.875rem;
      color: var(--text-primary);
      cursor: pointer;
      transition: all 0.2s ease;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 1rem;
      min-width: 160px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    select.input:hover {
      border-color: var(--primary);
      box-shadow: 0 2px 4px rgba(99, 102, 241, 0.1);
    }

    select.input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    select.input option {
      padding: 0.5rem;
      font-size: 0.875rem;
      background-color: var(--bg-white);
      color: var(--text-primary);
    }

    select.input option:hover {
      background-color: rgba(99, 102, 241, 0.1);
    }

    /* Button Styling */
    .button {
      padding: 0.625rem 1.25rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
      border: 1px solid transparent;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .button:hover {
      transform: translateY(-1px);
    }

    .button.button-primary {
      background-color: var(--primary);
      color: white;
      text-decoration: none;
    }

    .button.button-primary:hover {
      background-color: var(--primary-dark);
      box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }

    /* Container for select to maintain consistent spacing */
    .select-container {
      position: relative;
      display: inline-block;
    }

    .select-container::after {
      content: '';
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      width: 1rem;
      height: 1rem;
      opacity: 0.5;
      transition: opacity 0.2s ease;
    }

    .select-container:hover::after {
      opacity: 1;
    }

    /* Quick Actions Styling */
    .quick-actions {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .quick-action-button {
      width: 100%;
      padding: 0.875rem 1rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.2s ease;
      background-color: var(--bg-white);
      border: 1px solid rgba(99, 102, 241, 0.1);
      color: var(--text-primary);
      cursor: pointer;
    }

    .quick-action-button:hover {
      background-color: rgba(99, 102, 241, 0.05);
      border-color: var(--primary);
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(99, 102, 241, 0.1);
    }

    .quick-action-button.primary {
      background-color: var(--primary);
      color: white;
      border: none;
    }

    .quick-action-button.primary:hover {
      background-color: var(--primary-dark);
      box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
    }

    .quick-action-button svg {
      width: 1.25rem;
      height: 1.25rem;
      opacity: 0.8;
      transition: opacity 0.2s ease;
    }

    .quick-action-button:hover svg {
      opacity: 1;
    }

    .quick-action-button.primary svg {
      color: white;
    }

    .quick-actions-divider {
      height: 1px;
      background: rgba(99, 102, 241, 0.1);
      margin: 0.5rem 0;
    }

    /* Modal Styling */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal {
      background: var(--bg-white);
      border-radius: 1rem;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .modal-header {
      padding: 1.5rem;
      border-bottom: 1px solid rgba(99, 102, 241, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      background: var(--bg-white);
      z-index: 1;
    }

    .modal-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .modal-close {
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 0.375rem;
      transition: all 0.2s ease;
    }

    .modal-close:hover {
      background: rgba(99, 102, 241, 0.1);
      color: var(--primary);
    }

    .modal-body {
      padding: 1.5rem;
    }

    /* Tab Menu Styling */
    .tab-menu {
      display: flex;
      gap: 0.5rem;
      border-bottom: 1px solid rgba(99, 102, 241, 0.1);
      margin-bottom: 1.5rem;
    }

    .tab-button {
      padding: 0.75rem 1.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-secondary);
      background: none;
      border: none;
      border-bottom: 2px solid transparent;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .tab-button:hover {
      color: var(--primary);
    }

    .tab-button.active {
      color: var(--primary);
      border-bottom-color: var(--primary);
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    /* Campaign Details Styling */
    .campaign-details {
      display: grid;
      gap: 1.5rem;
    }

    .detail-group {
      display: grid;
      gap: 0.5rem;
    }

    .detail-label {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-secondary);
    }

    .detail-value {
      font-size: 1rem;
      color: var(--text-primary);
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    /* Contract Tab Styling */
    .contract-section {
      background: var(--bg-light);
      border-radius: 0.5rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .contract-status {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 500;
      background: rgba(99, 102, 241, 0.1);
      color: var(--primary);
    }

    .contract-actions {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    /* Payment Tab Styles */
    .payment-section {
      padding: 1rem 0;
    }

    .payment-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(99, 102, 241, 0.1);
    }

    .payment-status {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .payment-amount {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .payment-timeline {
      margin: 2rem 0;
      position: relative;
      padding-left: 2rem;
    }

    .payment-timeline::before {
      content: '';
      position: absolute;
      left: 0.5rem;
      top: 0;
      bottom: 0;
      width: 2px;
      background: rgba(99, 102, 241, 0.1);
    }

    .timeline-item {
      position: relative;
      padding-bottom: 2rem;
    }

    .timeline-item:last-child {
      padding-bottom: 0;
    }

    .timeline-marker {
      position: absolute;
      left: -2rem;
      width: 1.5rem;
      height: 1.5rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg-white);
      border: 2px solid var(--primary);
    }

    .timeline-marker.completed {
      background: var(--primary);
      color: white;
    }

    .timeline-marker.pending {
      border-color: var(--text-secondary);
      color: var(--text-secondary);
    }

    .timeline-content {
      background: var(--bg-light);
      border-radius: 0.5rem;
      padding: 1rem;
    }

    .timeline-title {
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .timeline-subtitle {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin-bottom: 0.5rem;
    }

    .timeline-amount {
      font-weight: 600;
      color: var(--primary);
    }

    .payment-details {
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(99, 102, 241, 0.1);
    }

    .details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-top: 1rem;
    }

    .detail-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .detail-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .detail-value {
      font-weight: 500;
      color: var(--text-primary);
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="campaigns.php" class="nav-link active">Campaigns</a>
        <a href="influencers.php" class="nav-link">Influencers</a>
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
          <h1 class="welcome-text">Campaign Management</h1>
          <p class="text-secondary">Create and manage your influencer marketing campaigns</p>
        </div>
        <div class="flex gap-2">
          <button class="button">Filter Campaigns</button>
          <a href="create_campaign.php" class="button button-primary">Create Campaign</a>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Active Campaigns</div>
          <div class="stat-value">8</div>
          <div class="stat-change positive">+2 from last month</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Total Budget</div>
          <div class="stat-value">$85.5K</div>
          <div class="stat-change">Allocated</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Campaign Reach</div>
          <div class="stat-value">3.2M</div>
          <div class="stat-change positive">+18% increase</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Success Rate</div>
          <div class="stat-value">92%</div>
          <div class="stat-change positive">+5% from target</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Active Campaigns</h2>
              <div class="flex gap-2">
                <button class="button">All</button>
                <button class="button button-primary">Active</button>
                <button class="button">Draft</button>
                <button class="button">Completed</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">
                    Active • 12 influencers • $25K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Fashion</span>
                    <span class="tag">Lifestyle</span>
                    <span class="tag">High Priority</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 75%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 75%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button" onclick="openModal()">View Details</button>
                  <a href="../chat/chat.php?campaign=1&influencer=sarah" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Chat
                  </a>
                  <button class="button button-primary">Manage</button>
                </div>
              </div>

              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">
                    Active • 8 influencers • $15K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Technology</span>
                    <span class="tag">Gaming</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Progress: 45%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 45%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button" onclick="openModal()">View Details</button>
                  <button class="button button-primary">Manage</button>
                </div>
              </div>

              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Holiday Special</div>
                  <div class="list-item-subtitle">
                    Draft • 15 influencers • $35K budget
                  </div>
                  <div class="tags">
                    <span class="tag">Seasonal</span>
                    <span class="tag">Shopping</span>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Not Started</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 0%"></div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button class="button">Edit</button>
                  <button class="button button-primary">Launch</button>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Performance</h2>
              <div class="flex gap-2">
                <div class="select-container">
                  <select class="input">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>Last 90 Days</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">
                    Reach: 1.2M • Engagement: 4.5% • ROI: 320%
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Performance: 92%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 92%"></div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">
                    Reach: 850K • Engagement: 3.8% • ROI: 280%
                  </div>
                </div>
                <div class="progress-container">
                  <div class="text-sm text-secondary mb-4">Performance: 85%</div>
                  <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 85%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Categories</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Fashion & Beauty</div>
                  <div class="list-item-subtitle">4 active campaigns</div>
                </div>
                <span class="badge badge-primary">Popular</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech & Gaming</div>
                  <div class="list-item-subtitle">2 active campaigns</div>
                </div>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Food & Travel</div>
                  <div class="list-item-subtitle">1 active campaign</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="quick-actions">
              <button class="quick-action-button primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 5v14M5 12h14" />
                </svg>
                Create New Campaign
              </button>
              <button class="quick-action-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="17 8 12 3 7 8" />
                  <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                Import Campaign
              </button>
              <button class="quick-action-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="7 10 12 15 17 10" />
                  <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Export Campaign Data
              </button>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Upcoming Deadlines</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">Content due in 3 days</div>
                </div>
                <span class="badge badge-warning">Urgent</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">Review due in 5 days</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <div class="modal-overlay" id="campaignModal">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title">Campaign Details</h2>
        <button class="modal-close" onclick="closeModal()">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="tab-menu">
          <button class="tab-button active" onclick="switchTab('details')">Campaign Details</button>
          <button class="tab-button" onclick="switchTab('contract')">Contract</button>
          <button class="tab-button" onclick="switchTab('payment')">Payment</button>
        </div>

        <div id="detailsTab" class="tab-content active">
          <div class="campaign-details">
            <div class="detail-grid">
              <div class="detail-group">
                <div class="detail-label">Campaign Title</div>
                <div class="detail-value">Summer Collection Launch</div>
              </div>
              <div class="detail-group">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                  <span class="badge badge-primary">Active</span>
                </div>
              </div>
              <div class="detail-group">
                <div class="detail-label">Budget</div>
                <div class="detail-value">$25,000</div>
              </div>
              <div class="detail-group">
                <div class="detail-label">Duration</div>
                <div class="detail-value">30 days</div>
              </div>
            </div>

            <div class="detail-group">
              <div class="detail-label">Description</div>
              <div class="detail-value">
                Launch campaign for the new summer collection featuring lifestyle content and product showcases.
              </div>
            </div>

            <div class="detail-group">
              <div class="detail-label">Target Audience</div>
              <div class="detail-value">
                Fashion-conscious individuals aged 18-35, interested in sustainable fashion and lifestyle content.
              </div>
            </div>

            <div class="detail-group">
              <div class="detail-label">Platforms</div>
              <div class="detail-value">
                <div class="tags">
                  <span class="tag">Instagram</span>
                  <span class="tag">TikTok</span>
                  <span class="tag">YouTube</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="contractTab" class="tab-content">
          <div class="contract-section">
            <div class="contract-status">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              Contract Status: Draft
            </div>
            
            <div class="contract-actions">
              <a href="create_contract.php?campaign_id=1" class="button button-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Create Contract
              </a>
              <button class="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                View Draft
              </button>
            </div>
          </div>
        </div>

        <div id="paymentTab" class="tab-content">
          <div class="payment-section">
            <div class="payment-header">
              <div class="payment-status">
                <span class="badge badge-primary">Payment Pending</span>
                <span class="payment-amount">$25,000</span>
              </div>
              <a href="process_payment.php?campaign_id=1" class="button button-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="7 10 12 15 17 10"></polyline>
                  <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Process Payment
              </a>
            </div>

            <div class="payment-timeline">
              <div class="timeline-item">
                <div class="timeline-marker completed">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                </div>
                <div class="timeline-content">
                  <div class="timeline-title">Initial Payment (50%)</div>
                  <div class="timeline-subtitle">Due: June 1, 2024</div>
                  <div class="timeline-amount">$12,500</div>
                </div>
              </div>

              <div class="timeline-item">
                <div class="timeline-marker pending">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                  </svg>
                </div>
                <div class="timeline-content">
                  <div class="timeline-title">Final Payment (50%)</div>
                  <div class="timeline-subtitle">Due: July 31, 2024</div>
                  <div class="timeline-amount">$12,500</div>
                </div>
              </div>
            </div>

            <div class="payment-details">
              <h3 class="section-title">Payment Details</h3>
              <div class="details-grid">
                <div class="detail-group">
                  <div class="detail-label">Payment Method</div>
                  <div class="detail-value">Bank Transfer</div>
                </div>
                <div class="detail-group">
                  <div class="detail-label">Account Holder</div>
                  <div class="detail-value">Sarah Johnson</div>
                </div>
                <div class="detail-group">
                  <div class="detail-label">Bank Account</div>
                  <div class="detail-value">**** **** **** 1234</div>
                </div>
                <div class="detail-group">
                  <div class="detail-label">Payment Terms</div>
                  <div class="detail-value">50% upfront, 50% upon completion</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/scriptt.js"></script>
  <script>
    // Modal Functions
    function openModal() {
      document.getElementById('campaignModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('campaignModal').classList.remove('active');
      document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.getElementById('campaignModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeModal();
      }
    });

    // Tab Switching
    function switchTab(tabName) {
      // Update tab buttons
      document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
      });
      event.target.classList.add('active');

      // Update tab content
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      document.getElementById(tabName + 'Tab').classList.add('active');
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });

    // Campaign Management Functions
    let campaigns = [];
    let currentFilters = {
        status: '',
        search: ''
    };

    // Load campaigns
    async function loadCampaigns() {
        try {
            const queryParams = new URLSearchParams(currentFilters);
            const response = await fetch(`php/business_api.php?endpoint=campaigns&${queryParams}`);
            const result = await response.json();
            
            if (result.success) {
                campaigns = result.data;
                renderCampaigns();
            } else {
                showToast('Failed to load campaigns', 'error');
            }
        } catch (error) {
            console.error('Error loading campaigns:', error);
            showToast('Error loading campaigns', 'error');
        }
    }

    // Render campaigns in the grid
    function renderCampaigns() {
        const grid = document.getElementById('campaignsGrid');
        if (!grid) return;

        grid.innerHTML = campaigns.map(campaign => `
            <div class="campaign-card" data-id="${campaign.id}">
                <div class="campaign-header">
                    <div>
                        <h3 class="campaign-title">${campaign.title}</h3>
                        <span class="campaign-category">${campaign.status}</span>
                    </div>
                    <div class="campaign-budget">$${parseFloat(campaign.budget).toLocaleString()}</div>
                </div>
                <p class="campaign-description">${campaign.description}</p>
                <div class="campaign-details">
                    <div class="campaign-detail">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Duration: ${new Date(campaign.start_date).toLocaleDateString()} - ${new Date(campaign.end_date).toLocaleDateString()}</span>
                    </div>
                </div>
                <div class="campaign-actions">
                    <button class="button" onclick="editCampaign(${campaign.id})">Edit</button>
                    <button class="button button-primary" onclick="viewCampaignDetails(${campaign.id})">View Details</button>
                    <button class="button button-danger" onclick="deleteCampaign(${campaign.id})">Delete</button>
                </div>
            </div>
        `).join('');
    }

    // Create new campaign
    async function createCampaign(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        const campaignData = {
            title: formData.get('title'),
            description: formData.get('description'),
            budget: formData.get('budget'),
            requirements: formData.get('requirements'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            status: 'draft'
        };

        try {
            const response = await fetch('php/business_api.php?endpoint=campaigns', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(campaignData)
            });

            const result = await response.json();
            
            if (result.success) {
                showToast('Campaign created successfully');
                closeModal('createCampaignModal');
                loadCampaigns();
            } else {
                showToast(result.error || 'Failed to create campaign', 'error');
            }
        } catch (error) {
            console.error('Error creating campaign:', error);
            showToast('Error creating campaign', 'error');
        }
    }

    // Edit campaign
    async function editCampaign(campaignId) {
        const campaign = campaigns.find(c => c.id === campaignId);
        if (!campaign) return;

        // Populate edit form
        const form = document.getElementById('editCampaignForm');
        if (form) {
            form.elements['id'].value = campaign.id;
            form.elements['title'].value = campaign.title;
            form.elements['description'].value = campaign.description;
            form.elements['budget'].value = campaign.budget;
            form.elements['requirements'].value = campaign.requirements;
            form.elements['start_date'].value = campaign.start_date;
            form.elements['end_date'].value = campaign.end_date;
            form.elements['status'].value = campaign.status;
            
            openModal('editCampaignModal');
        }
    }

    // Update campaign
    async function updateCampaign(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const campaignId = formData.get('id');
        
        const campaignData = {
            title: formData.get('title'),
            description: formData.get('description'),
            budget: formData.get('budget'),
            requirements: formData.get('requirements'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            status: formData.get('status')
        };

        try {
            const response = await fetch(`php/business_api.php?endpoint=campaigns&id=${campaignId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(campaignData)
            });

            const result = await response.json();
            
            if (result.success) {
                showToast('Campaign updated successfully');
                closeModal('editCampaignModal');
                loadCampaigns();
            } else {
                showToast(result.error || 'Failed to update campaign', 'error');
            }
        } catch (error) {
            console.error('Error updating campaign:', error);
            showToast('Error updating campaign', 'error');
        }
    }

    // Delete campaign
    async function deleteCampaign(campaignId) {
        if (!confirm('Are you sure you want to delete this campaign?')) return;

        try {
            const response = await fetch(`php/business_api.php?endpoint=campaigns&id=${campaignId}`, {
                method: 'DELETE'
            });

            const result = await response.json();
            
            if (result.success) {
                showToast('Campaign deleted successfully');
                loadCampaigns();
            } else {
                showToast(result.error || 'Failed to delete campaign', 'error');
            }
        } catch (error) {
            console.error('Error deleting campaign:', error);
            showToast('Error deleting campaign', 'error');
        }
    }

    // Filter campaigns
    function updateFilters() {
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('searchInput');
        
        currentFilters = {
            status: statusFilter.value,
            search: searchInput.value
        };
        
        loadCampaigns();
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
        loadCampaigns();
        
        // Add event listeners for filters
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('searchInput');
        
        if (statusFilter) {
            statusFilter.addEventListener('change', updateFilters);
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', debounce(updateFilters, 300));
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

  <!-- Campaign Modals -->
  <div id="createCampaignModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Create New Campaign</h2>
        <button class="close-button" onclick="closeModal('createCampaignModal')">&times;</button>
      </div>
      <form id="createCampaignForm" onsubmit="createCampaign(event)">
        <div class="form-group">
          <label for="title">Campaign Title</label>
          <input type="text" id="title" name="title" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" class="form-input" required></textarea>
        </div>
        <div class="form-group">
          <label for="budget">Budget</label>
          <input type="number" id="budget" name="budget" class="form-input" min="0" step="0.01" required>
        </div>
        <div class="form-group">
          <label for="requirements">Requirements</label>
          <textarea id="requirements" name="requirements" class="form-input"></textarea>
        </div>
        <div class="form-group">
          <label for="start_date">Start Date</label>
          <input type="date" id="start_date" name="start_date" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="end_date">End Date</label>
          <input type="date" id="end_date" name="end_date" class="form-input" required>
        </div>
        <div class="button-group">
          <button type="button" class="button" onclick="closeModal('createCampaignModal')">Cancel</button>
          <button type="submit" class="button button-primary">Create Campaign</button>
        </div>
      </form>
    </div>
  </div>

  <div id="editCampaignModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Campaign</h2>
        <button class="close-button" onclick="closeModal('editCampaignModal')">&times;</button>
      </div>
      <form id="editCampaignForm" onsubmit="updateCampaign(event)">
        <input type="hidden" name="id">
        <div class="form-group">
          <label for="edit_title">Campaign Title</label>
          <input type="text" id="edit_title" name="title" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="edit_description">Description</label>
          <textarea id="edit_description" name="description" class="form-input" required></textarea>
        </div>
        <div class="form-group">
          <label for="edit_budget">Budget</label>
          <input type="number" id="edit_budget" name="budget" class="form-input" min="0" step="0.01" required>
        </div>
        <div class="form-group">
          <label for="edit_requirements">Requirements</label>
          <textarea id="edit_requirements" name="requirements" class="form-input"></textarea>
        </div>
        <div class="form-group">
          <label for="edit_start_date">Start Date</label>
          <input type="date" id="edit_start_date" name="start_date" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="edit_end_date">End Date</label>
          <input type="date" id="edit_end_date" name="end_date" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="edit_status">Status</label>
          <select id="edit_status" name="status" class="form-input" required>
            <option value="draft">Draft</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="button-group">
          <button type="button" class="button" onclick="closeModal('editCampaignModal')">Cancel</button>
          <button type="submit" class="button button-primary">Update Campaign</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>