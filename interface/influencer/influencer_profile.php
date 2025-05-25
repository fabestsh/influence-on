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
  <title>Profile Management - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <style>
    .profile-header {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 2rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
      position: relative;
    }
    
    .profile-cover {
      height: 200px;
      background: linear-gradient(to right, var(--primary), var(--primary-light));
      border-radius: 0.5rem;
      margin-bottom: 4rem;
    }
    
    .profile-avatar {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 4px solid var(--bg-white);
      position: absolute;
      top: 100px;
      left: 2rem;
      box-shadow: var(--shadow-md);
      cursor: pointer;
      transition: filter 0.2s ease;
    }
    
    .profile-avatar:hover {
      filter: brightness(0.9);
    }
    
    .profile-avatar::after {
      content: 'Change Photo';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      opacity: 0;
      transition: opacity 0.2s ease;
      font-size: 0.875rem;
      font-weight: 500;
    }
    
    .profile-avatar:hover::after {
      opacity: 1;
    }
    
    .profile-info {
      margin-left: 180px;
    }
    
    .profile-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .profile-stat {
      text-align: center;
    }
    
    .profile-stat-value {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
    }
    
    .profile-stat-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }
    
    .profile-section {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
    }
    
    .profile-section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
    
    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }
    
    .form-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }
    
    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }
    
    .form-textarea {
      min-height: 100px;
      resize: vertical;
    }
    
    .social-accounts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }
    
    .social-account {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
    }
    
    .social-icon {
      width: 24px;
      height: 24px;
      color: var(--text-secondary);
    }
    
    .category-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    
    .category-tag {
      padding: 0.5rem 1rem;
      background: var(--bg-light);
      border-radius: 2rem;
      font-size: 0.875rem;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    
    .category-tag:hover {
      background-color: var(--bg-hover);
    }
    
    .category-tag button {
      color: var(--text-secondary);
      opacity: 0.5;
      transition: opacity 0.2s ease;
    }
    
    .category-tag button:hover {
      opacity: 1;
    }
    
    .portfolio-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
    }
    
    .portfolio-item {
      position: relative;
      border-radius: 0.5rem;
      overflow: hidden;
      aspect-ratio: 1;
      transition: transform 0.2s ease;
    }
    
    .portfolio-item:hover {
      transform: scale(1.02);
    }
    
    .portfolio-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .portfolio-item-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
    }
    
    .portfolio-item:hover .portfolio-item-overlay {
      opacity: 1;
    }
    
    .verification-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.25rem 0.75rem;
      background: var(--success-light);
      color: var(--success);
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 500;
    }
    
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    
    .modal.active {
      display: flex;
    }
    
    .modal-content {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 2rem;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
    }
    
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .modal-close {
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.5rem;
      transition: color 0.2s ease;
    }
    
    .modal-close:hover {
      color: var(--text-primary);
    }
    
    /* Account Settings Improvements */
    .account-settings {
      display: grid;
      gap: 1rem;
    }
    
    .account-settings-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: 0.5rem;
      transition: background-color 0.2s ease, transform 0.2s ease;
      cursor: pointer;
    }
    
    .account-settings-item:hover {
      background-color: var(--bg-hover);
      transform: translateX(4px);
    }
    
    .account-settings-info {
      flex: 1;
    }
    
    .account-settings-title {
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .account-settings-description {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin-top: 0.25rem;
    }
    
    /* Profile Image Upload */
    .profile-image-upload {
      position: relative;
      width: 150px;
      height: 150px;
      margin: 0 auto;
    }
    
    .profile-image-upload img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .profile-image-upload-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
      cursor: pointer;
    }
    
    .profile-image-upload:hover .profile-image-upload-overlay {
      opacity: 1;
    }
    
    /* Stats Editor */
    .stats-editor {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .stat-input-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    
    .stat-input-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }
    
    .stat-input {
      padding: 0.5rem;
      border: 1px solid var(--border-color);
      border-radius: 0.25rem;
      font-size: 1rem;
    }
    
    /* Social Media Account Editor */
    .social-account-editor {
      display: grid;
      gap: 1rem;
    }
    
    .social-platform-select {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    .platform-option {
      padding: 0.5rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.25rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .platform-option.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    /* Add pointer cursors to clickable elements */
    .button,
    .nav-link,
    .account-settings-item,
    .social-account button,
    .category-tag button,
    .portfolio-item,
    .modal-close,
    .platform-option,
    .switch,
    .profile-avatar,
    .profile-image-upload-overlay {
      cursor: pointer;
    }

    /* Add hover effect to account settings items */
    .account-settings-item {
      cursor: pointer;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .account-settings-item:hover {
      background-color: var(--bg-hover);
      transform: translateX(4px);
    }

    /* Add hover effect to social account buttons */
    .social-account button {
      transition: background-color 0.2s ease;
    }

    .social-account button:hover {
      background-color: var(--bg-hover);
    }

    /* Add hover effect to category tags */
    .category-tag {
      transition: background-color 0.2s ease;
    }

    .category-tag:hover {
      background-color: var(--bg-hover);
    }

    /* Add hover effect to platform options */
    .platform-option {
      transition: all 0.2s ease;
    }

    .platform-option:hover {
      background-color: var(--bg-hover);
    }

    /* Add hover effect to portfolio items */
    .portfolio-item {
      transition: transform 0.2s ease;
    }

    .portfolio-item:hover {
      transform: scale(1.02);
    }

    /* Add styles for the new modals */
    .banner-preview,
    .content-preview {
      width: 100%;
      height: 150px;
      border-radius: 0.5rem;
      overflow: hidden;
      margin-bottom: 1rem;
      background: var(--bg-light);
    }

    .banner-preview img,
    .content-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .category-input-group {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .category-input-group input {
      flex: 1;
    }

    .category-list {
      max-height: 200px;
      overflow-y: auto;
      margin-bottom: 1rem;
    }

    .category-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.5rem;
      background: var(--bg-light);
      border-radius: 0.25rem;
      margin-bottom: 0.5rem;
    }

    .category-item button {
      color: var(--danger);
      opacity: 0.5;
      transition: opacity 0.2s ease;
    }

    .category-item button:hover {
      opacity: 1;
    }

    .profile-cover {
      cursor: pointer;
      position: relative;
    }

    .profile-cover::after {
      content: 'Change Banner';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .profile-cover:hover::after {
      opacity: 1;
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencer_analytics.php" class="nav-link">Analytics</a>
        <a href="influencer_messages.php" class="nav-link">Messages</a>
        <form method="POST" action="../../interface/auth/php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Profile Image Upload Modal -->
  <div class="modal" id="profileImageModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Update Profile Image</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="profile-image-upload">
        <img src="https://i.pravatar.cc/300" alt="Profile avatar" id="profilePreview" />
        <div class="profile-image-upload-overlay">
          <input type="file" id="profileImageInput" accept="image/*" style="display: none;" />
          <button class="button button-primary">Change Photo</button>
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="button" onclick="closeModal('profileImageModal')">Cancel</button>
        <button class="button button-primary" onclick="saveProfileImage()">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- Stats Update Modal -->
  <div class="modal" id="statsModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Update Statistics</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="stats-editor">
        <div class="stat-input-group">
          <label class="stat-input-label">Total Followers</label>
          <input type="number" class="stat-input" id="followersInput" value="500000" />
        </div>
        <div class="stat-input-group">
          <label class="stat-input-label">Engagement Rate (%)</label>
          <input type="number" class="stat-input" id="engagementInput" value="5.2" step="0.1" />
        </div>
        <div class="stat-input-group">
          <label class="stat-input-label">Total Campaigns</label>
          <input type="number" class="stat-input" id="campaignsInput" value="45" />
        </div>
        <div class="stat-input-group">
          <label class="stat-input-label">Average Rating</label>
          <input type="number" class="stat-input" id="ratingInput" value="4.9" step="0.1" />
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="button" onclick="closeModal('statsModal')">Cancel</button>
        <button class="button button-primary" onclick="saveStats()">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- Social Media Account Modal -->
  <div class="modal" id="socialAccountModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Add Social Media Account</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="social-account-editor">
        <div class="social-platform-select">
          <div class="platform-option active" data-platform="instagram">Instagram</div>
          <div class="platform-option" data-platform="twitter">Twitter</div>
          <div class="platform-option" data-platform="linkedin">LinkedIn</div>
          <div class="platform-option" data-platform="tiktok">TikTok</div>
          <div class="platform-option" data-platform="youtube">YouTube</div>
        </div>
        <div class="form-group">
          <label class="form-label">Username/Handle</label>
          <input type="text" class="form-input" id="socialUsername" placeholder="@username" />
        </div>
        <div class="form-group">
          <label class="form-label">Profile URL</label>
          <input type="url" class="form-input" id="socialUrl" placeholder="https://..." />
        </div>
        <div class="form-group">
          <label class="form-label">Followers Count</label>
          <input type="number" class="form-input" id="socialFollowers" placeholder="0" />
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="button" onclick="closeModal('socialAccountModal')">Cancel</button>
        <button class="button button-primary" onclick="saveSocialAccount()">Add Account</button>
      </div>
    </div>
  </div>

  <!-- Password Change Modal -->
  <div class="modal" id="passwordModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Change Password</h2>
        <button class="modal-close">&times;</button>
      </div>
      <form class="form-grid" onsubmit="return updatePassword(event)">
        <div class="form-group">
          <label class="form-label">Current Password</label>
          <input type="password" class="form-input" required />
        </div>
        <div class="form-group">
          <label class="form-label">New Password</label>
          <input type="password" class="form-input" required />
        </div>
        <div class="form-group">
          <label class="form-label">Confirm New Password</label>
          <input type="password" class="form-input" required />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button type="button" class="button" onclick="closeModal('passwordModal')">Cancel</button>
          <button type="submit" class="button button-primary">Update Password</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Payment Info Modal -->
  <div class="modal" id="paymentModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Update Payment Information</h2>
        <button class="modal-close">&times;</button>
      </div>
      <form class="form-grid" onsubmit="return updatePaymentInfo(event)">
        <div class="form-group">
          <label class="form-label">Payment Method</label>
          <select class="form-input" required>
            <option value="bank">Bank Transfer</option>
            <option value="paypal">PayPal</option>
            <option value="stripe">Stripe</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Account Name</label>
          <input type="text" class="form-input" required />
        </div>
        <div class="form-group">
          <label class="form-label">Account Number</label>
          <input type="text" class="form-input" required />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button type="button" class="button" onclick="closeModal('paymentModal')">Cancel</button>
          <button type="submit" class="button button-primary">Save Payment Info</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification Settings Modal -->
  <div class="modal" id="notificationsModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Notification Settings</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="list">
        <div class="list-item">
          <div class="list-item-content">
            <div class="list-item-title">Campaign Updates</div>
            <div class="list-item-subtitle">Get notified about campaign status changes</div>
          </div>
          <label class="switch">
            <input type="checkbox" checked />
            <span class="slider"></span>
          </label>
        </div>
        <div class="list-item">
          <div class="list-item-content">
            <div class="list-item-title">New Messages</div>
            <div class="list-item-subtitle">Receive notifications for new messages</div>
          </div>
          <label class="switch">
            <input type="checkbox" checked />
            <span class="slider"></span>
          </label>
        </div>
        <div class="list-item">
          <div class="list-item-content">
            <div class="list-item-title">Payment Updates</div>
            <div class="list-item-subtitle">Get notified about payment status</div>
          </div>
          <label class="switch">
            <input type="checkbox" checked />
            <span class="slider"></span>
          </label>
        </div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button class="button" onclick="closeModal('notificationsModal')">Cancel</button>
        <button class="button button-primary" onclick="saveNotificationSettings()">Save Settings</button>
      </div>
    </div>
  </div>

  <!-- Delete Account Modal -->
  <div class="modal" id="deleteAccountModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Delete Account</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="text-center">
        <p class="text-secondary mb-4">Are you sure you want to delete your account? This action cannot be undone.</p>
        <form onsubmit="return deleteAccount(event)">
          <div class="form-group">
            <label class="form-label">Enter your password to confirm</label>
            <input type="password" class="form-input" required />
          </div>
          <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="button" onclick="closeModal('deleteAccountModal')">Cancel</button>
            <button type="submit" class="button button-danger">Delete Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Banner Upload Modal -->
  <div class="modal" id="bannerModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Update Banner Image</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="banner-preview">
        <img src="" alt="Banner preview" id="bannerPreview" />
      </div>
      <div class="flex justify-center mb-4">
        <input type="file" id="bannerInput" accept="image/*" style="display: none;" />
        <button class="button button-primary" onclick="document.getElementById('bannerInput').click()">
          Choose Image
        </button>
      </div>
      <div class="flex justify-end gap-2">
        <button class="button" onclick="closeModal('bannerModal')">Cancel</button>
        <button class="button button-primary" onclick="saveBanner()">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- Content Upload Modal -->
  <div class="modal" id="contentModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Add Portfolio Content</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="content-preview">
        <img src="" alt="Content preview" id="contentPreview" />
      </div>
      <form onsubmit="return saveContent(event)">
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" class="form-input" id="contentTitle" required />
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-input form-textarea" id="contentDescription" required></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Categories</label>
          <div class="category-tags" id="contentCategories"></div>
          <input type="text" class="form-input mt-2" id="contentCategoryInput" placeholder="Add a category" />
        </div>
        <div class="flex justify-center mb-4">
          <input type="file" id="contentInput" accept="image/*" style="display: none;" />
          <button type="button" class="button button-primary" onclick="document.getElementById('contentInput').click()">
            Choose Image
          </button>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" class="button" onclick="closeModal('contentModal')">Cancel</button>
          <button type="submit" class="button button-primary">Add Content</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Category Management Modal -->
  <div class="modal" id="categoryModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="card-title">Manage Categories</h2>
        <button class="modal-close">&times;</button>
      </div>
      <div class="category-input-group">
        <input type="text" class="form-input" id="categoryInput" placeholder="Enter category name" />
        <button class="button button-primary" onclick="addCategory()">Add</button>
      </div>
      <div class="category-list" id="categoryList">
        <!-- Categories will be added here dynamically -->
      </div>
      <div class="flex justify-end gap-2">
        <button class="button" onclick="closeModal('categoryModal')">Done</button>
      </div>
    </div>
  </div>

  <main class="dashboard">
    <div class="container">
      <div class="profile-header">
        <div class="profile-cover"></div>
        <img src="https://i.pravatar.cc/300" alt="Profile avatar" class="profile-avatar" />
        <div class="profile-info">
          <div class="flex items-center gap-2">
            <h1 class="text-2xl font-semibold">Emma Wilson</h1>
            <span class="verification-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              Verified Creator
            </span>
          </div>
          <p class="text-secondary mt-1">Fashion & Lifestyle Influencer</p>
          <div class="profile-stats">
            <div class="profile-stat">
              <div class="profile-stat-value">500K</div>
              <div class="profile-stat-label">Followers</div>
            </div>
            <div class="profile-stat">
              <div class="profile-stat-value">5.2%</div>
              <div class="profile-stat-label">Engagement</div>
            </div>
            <div class="profile-stat">
              <div class="profile-stat-value">45</div>
              <div class="profile-stat-label">Campaigns</div>
            </div>
            <div class="profile-stat">
              <div class="profile-stat-value">4.9</div>
              <div class="profile-stat-label">Rating</div>
            </div>
          </div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <!-- Personal Information -->
          <div class="profile-section">
            <div class="profile-section-header">
              <h2 class="card-title">Personal Information</h2>
              <div class="flex gap-2">
                <button class="button" onclick="openModal('statsModal')">Update Stats</button>
                <button class="button button-primary" onclick="savePersonalInfo()">Save Changes</button>
              </div>
            </div>
            <form class="form-grid">
              <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-input" value="Emma Wilson" />
              </div>
              <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" value="emma@example.com" />
              </div>
              <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-input" value="+1 (555) 123-4567" />
              </div>
              <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" class="form-input" value="New York, USA" />
              </div>
              <div class="form-group col-span-2">
                <label class="form-label">Bio</label>
                <textarea class="form-input form-textarea">Fashion enthusiast and lifestyle content creator. Passionate about sustainable fashion and helping brands connect with their audience through authentic storytelling.</textarea>
              </div>
            </form>
          </div>

          <!-- Social Media Accounts -->
          <div class="profile-section">
            <div class="profile-section-header">
              <h2 class="card-title">Social Media Accounts</h2>
              <button class="button button-primary" onclick="openModal('socialAccountModal')">Add Account</button>
            </div>
            <div class="social-accounts">
              <div class="social-account">
                <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                </svg>
                <div class="flex-1">
                  <div class="font-medium">Twitter</div>
                  <div class="text-sm text-secondary">@emmawilson</div>
                </div>
                <button class="button">Edit</button>
              </div>
              <div class="social-account">
                <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
                <div class="flex-1">
                  <div class="font-medium">Instagram</div>
                  <div class="text-sm text-secondary">@emmawilson</div>
                </div>
                <button class="button">Edit</button>
              </div>
              <div class="social-account">
                <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
                <div class="flex-1">
                  <div class="font-medium">LinkedIn</div>
                  <div class="text-sm text-secondary">Emma Wilson</div>
                </div>
                <button class="button">Edit</button>
              </div>
            </div>
          </div>

          <!-- Content Categories -->
          <div class="profile-section">
            <div class="profile-section-header">
              <h2 class="card-title">Content Categories</h2>
              <button class="button button-primary">Add Category</button>
            </div>
            <div class="category-tags">
              <div class="category-tag">
                Fashion
                <button type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
              </div>
              <div class="category-tag">
                Lifestyle
                <button type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
              </div>
              <div class="category-tag">
                Beauty
                <button type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Portfolio -->
          <div class="profile-section">
            <div class="profile-section-header">
              <h2 class="card-title">Portfolio</h2>
              <button class="button button-primary">Add Content</button>
            </div>
            <div class="portfolio-grid">
              <div class="portfolio-item">
                <img src="https://picsum.photos/400/400?random=1" alt="Portfolio item" />
                <div class="portfolio-item-overlay">
                  <button class="button button-primary">View Details</button>
                </div>
              </div>
              <div class="portfolio-item">
                <img src="https://picsum.photos/400/400?random=2" alt="Portfolio item" />
                <div class="portfolio-item-overlay">
                  <button class="button button-primary">View Details</button>
                </div>
              </div>
              <div class="portfolio-item">
                <img src="https://picsum.photos/400/400?random=3" alt="Portfolio item" />
                <div class="portfolio-item-overlay">
                  <button class="button button-primary">View Details</button>
                </div>
              </div>
              <div class="portfolio-item">
                <img src="https://picsum.photos/400/400?random=4" alt="Portfolio item" />
                <div class="portfolio-item-overlay">
                  <button class="button button-primary">View Details</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <!-- Account Settings -->
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Account Settings</h2>
            </div>
            <div class="account-settings">
              <div class="account-settings-item" onclick="openModal('passwordModal')">
                <div class="account-settings-info">
                  <div class="account-settings-title">Change Password</div>
                  <div class="account-settings-description">Update your account password</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                  <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                  <path d="M2 2l7.586 7.586"></path>
                  <circle cx="11" cy="11" r="2"></circle>
                </svg>
              </div>
              <div class="account-settings-item" onclick="openModal('paymentModal')">
                <div class="account-settings-info">
                  <div class="account-settings-title">Payment Information</div>
                  <div class="account-settings-description">Update your payment details</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                  <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
              </div>
              <div class="account-settings-item" onclick="openModal('notificationsModal')">
                <div class="account-settings-info">
                  <div class="account-settings-title">Notification Settings</div>
                  <div class="account-settings-description">Manage your notification preferences</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
              </div>
              <div class="account-settings-item" onclick="openModal('deleteAccountModal')">
                <div class="account-settings-info">
                  <div class="account-settings-title text-danger">Delete Account</div>
                  <div class="account-settings-description">Permanently delete your account</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Verification Status -->
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Verification Status</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Identity Verification</div>
                  <div class="list-item-subtitle">Verified on May 15, 2024</div>
                </div>
                <span class="badge badge-success">Verified</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Payment Verification</div>
                  <div class="list-item-subtitle">Verified on May 15, 2024</div>
                </div>
                <span class="badge badge-success">Verified</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../js/script.js"></script>
  <script>
    // Modal Management
    const modals = {
      profileImage: document.getElementById('profileImageModal'),
      stats: document.getElementById('statsModal'),
      socialAccount: document.getElementById('socialAccountModal'),
      password: document.getElementById('passwordModal'),
      payment: document.getElementById('paymentModal'),
      notifications: document.getElementById('notificationsModal'),
      deleteAccount: document.getElementById('deleteAccountModal'),
      banner: document.getElementById('bannerModal'),
      content: document.getElementById('contentModal'),
      category: document.getElementById('categoryModal')
    };

    // Open modal with animation
    function openModal(modalId) {
      const modal = modals[modalId.replace('Modal', '')];
      if (modal) {
        modal.style.display = 'flex';
        // Trigger reflow for animation
        modal.offsetHeight;
        modal.classList.add('active');
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
      }
    }

    // Close modal with animation
    function closeModal(modalId) {
      const modal = modals[modalId.replace('Modal', '')];
      if (modal) {
        modal.classList.remove('active');
        // Wait for animation to complete
        setTimeout(() => {
          modal.style.display = 'none';
          // Restore body scroll
          document.body.style.overflow = '';
        }, 300);
      }
    }

    // Close modals when clicking outside
    Object.values(modals).forEach(modal => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          closeModal(modal.id);
        }
      });
    });

    // Close modals when clicking close button
    document.querySelectorAll('.modal-close').forEach(button => {
      button.addEventListener('click', () => {
        const modal = button.closest('.modal');
        closeModal(modal.id);
      });
    });

    // Profile Image Upload Enhancement
    const mainProfileAvatar = document.querySelector('.profile-avatar');
    const profileImageInput = document.createElement('input');
    profileImageInput.type = 'file';
    profileImageInput.accept = 'image/*';
    profileImageInput.style.display = 'none';
    document.body.appendChild(profileImageInput);

    // Handle main profile avatar click
    mainProfileAvatar.addEventListener('click', () => {
      profileImageInput.click();
    });

    // Handle file selection
    profileImageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            // Update both the main avatar and the modal preview
            mainProfileAvatar.src = e.target.result;
            const modalPreview = document.getElementById('profilePreview');
            if (modalPreview) {
              modalPreview.src = e.target.result;
            }
            showToast('Profile image updated successfully');
          };
          reader.readAsDataURL(file);
        } else {
          showToast('Please select an image file', 'error');
          this.value = '';
        }
      }
    });

    // Also handle the modal profile image upload
    const modalProfileImageInput = document.getElementById('profileImageInput');
    const modalProfilePreview = document.getElementById('profilePreview');

    modalProfileImageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            modalProfilePreview.src = e.target.result;
            // Update main profile avatar as well
            mainProfileAvatar.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          showToast('Please select an image file', 'error');
          this.value = '';
        }
      }
    });

    // Update saveProfileImage function
    function saveProfileImage() {
      const file = modalProfileImageInput.files[0];
      if (file) {
        // Simulate upload delay
        setTimeout(() => {
          closeModal('profileImageModal');
          showToast('Profile image updated successfully');
        }, 1000);
      } else {
        showToast('Please select an image to upload', 'error');
      }
    }

    // Platform Selection in Social Media Modal
    const platformOptions = document.querySelectorAll('.platform-option');
    platformOptions.forEach(option => {
      option.addEventListener('click', function() {
        platformOptions.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        
        // Update placeholder based on platform
        const platform = this.dataset.platform;
        const usernameInput = document.getElementById('socialUsername');
        const urlInput = document.getElementById('socialUrl');
        
        switch(platform) {
          case 'instagram':
            usernameInput.placeholder = '@username';
            urlInput.placeholder = 'https://instagram.com/username';
            break;
          case 'twitter':
            usernameInput.placeholder = '@handle';
            urlInput.placeholder = 'https://twitter.com/handle';
            break;
          case 'linkedin':
            usernameInput.placeholder = 'Full Name';
            urlInput.placeholder = 'https://linkedin.com/in/username';
            break;
          case 'tiktok':
            usernameInput.placeholder = '@username';
            urlInput.placeholder = 'https://tiktok.com/@username';
            break;
          case 'youtube':
            usernameInput.placeholder = 'Channel Name';
            urlInput.placeholder = 'https://youtube.com/c/channelname';
            break;
        }
      });
    });

    // Stats Update Preview
    function updateStatsPreview() {
      const followers = document.getElementById('followersInput').value;
      const engagement = document.getElementById('engagementInput').value;
      const campaigns = document.getElementById('campaignsInput').value;
      const rating = document.getElementById('ratingInput').value;

      // Update stats in the profile header
      document.querySelector('.profile-stat:nth-child(1) .profile-stat-value').textContent = 
        formatNumber(followers);
      document.querySelector('.profile-stat:nth-child(2) .profile-stat-value').textContent = 
        engagement + '%';
      document.querySelector('.profile-stat:nth-child(3) .profile-stat-value').textContent = 
        campaigns;
      document.querySelector('.profile-stat:nth-child(4) .profile-stat-value').textContent = 
        rating;
    }

    // Helper function to format numbers
    function formatNumber(num) {
      if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
      } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
      }
      return num;
    }

    // Add input event listeners for stats preview
    ['followersInput', 'engagementInput', 'campaignsInput', 'ratingInput'].forEach(id => {
      document.getElementById(id).addEventListener('input', updateStatsPreview);
    });

    // Form Submission Handlers with UI Feedback
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);
      
      // Trigger animation
      setTimeout(() => toast.classList.add('show'), 100);
      
      // Remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // Add toast styles
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);

    function saveStats() {
      updateStatsPreview();
      closeModal('statsModal');
      showToast('Statistics updated successfully');
    }

    function saveSocialAccount() {
      const platform = document.querySelector('.platform-option.active').dataset.platform;
      const username = document.getElementById('socialUsername').value;
      const url = document.getElementById('socialUrl').value;
      const followers = document.getElementById('socialFollowers').value;

      if (username && url) {
        // Add new social account to the list
        const socialAccounts = document.querySelector('.social-accounts');
        const newAccount = document.createElement('div');
        newAccount.className = 'social-account';
        newAccount.innerHTML = `
          <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0z"/>
          </svg>
          <div class="flex-1">
            <div class="font-medium">${platform.charAt(0).toUpperCase() + platform.slice(1)}</div>
            <div class="text-sm text-secondary">${username}</div>
          </div>
          <button class="button">Edit</button>
        `;
        socialAccounts.appendChild(newAccount);
        
        closeModal('socialAccountModal');
        showToast('Social media account added successfully');
      } else {
        showToast('Please fill in all required fields', 'error');
      }
    }

    function updatePassword(event) {
      event.preventDefault();
      const form = event.target;
      const currentPassword = form.querySelector('input[type="password"]:nth-child(1)').value;
      const newPassword = form.querySelector('input[type="password"]:nth-child(2)').value;
      const confirmPassword = form.querySelector('input[type="password"]:nth-child(3)').value;

      if (newPassword === confirmPassword) {
        // Simulate password update
        setTimeout(() => {
          closeModal('passwordModal');
          showToast('Password updated successfully');
          form.reset();
        }, 1000);
      } else {
        showToast('New passwords do not match', 'error');
      }
    }

    function updatePaymentInfo(event) {
      event.preventDefault();
      const form = event.target;
      const paymentMethod = form.querySelector('select').value;
      const accountName = form.querySelector('input[type="text"]:nth-child(1)').value;
      const accountNumber = form.querySelector('input[type="text"]:nth-child(2)').value;

      if (accountName && accountNumber) {
        // Simulate payment info update
        setTimeout(() => {
          closeModal('paymentModal');
          showToast('Payment information updated successfully');
          form.reset();
        }, 1000);
      } else {
        showToast('Please fill in all required fields', 'error');
      }
    }

    function saveNotificationSettings() {
      const toggles = document.querySelectorAll('#notificationsModal input[type="checkbox"]');
      const enabled = Array.from(toggles).filter(toggle => toggle.checked).length;
      
      closeModal('notificationsModal');
      showToast(`${enabled} notification settings updated`);
    }

    function deleteAccount(event) {
      event.preventDefault();
      const password = event.target.querySelector('input[type="password"]').value;
      
      if (password) {
        // Simulate account deletion
        setTimeout(() => {
          closeModal('deleteAccountModal');
          showToast('Account deleted successfully');
          // Redirect to logout after 2 seconds
          setTimeout(() => {
            window.location.href = '../../interface/auth/php/logout.php';
          }, 2000);
        }, 1000);
      } else {
        showToast('Please enter your password to confirm', 'error');
      }
    }

    function savePersonalInfo() {
      const form = document.querySelector('.profile-section form');
      const inputs = form.querySelectorAll('input, textarea');
      let isValid = true;

      inputs.forEach(input => {
        if (input.required && !input.value) {
          isValid = false;
        }
      });

      if (isValid) {
        showToast('Personal information updated successfully');
      } else {
        showToast('Please fill in all required fields', 'error');
      }
    }

    // Initialize tooltips for social media icons
    document.querySelectorAll('.social-icon').forEach(icon => {
      icon.title = icon.closest('.social-account').querySelector('.font-medium').textContent;
    });

    // Add hover effect for portfolio items
    document.querySelectorAll('.portfolio-item').forEach(item => {
      item.addEventListener('mouseenter', () => {
        item.querySelector('.portfolio-item-overlay').style.opacity = '1';
      });
      item.addEventListener('mouseleave', () => {
        item.querySelector('.portfolio-item-overlay').style.opacity = '0';
      });
    });

    // Banner Upload Handling
    const bannerInput = document.getElementById('bannerInput');
    const bannerPreview = document.getElementById('bannerPreview');
    const profileBanner = document.querySelector('.profile-cover');
    let currentBannerFile = null;

    // Make profile cover clickable
    document.querySelector('.profile-cover').addEventListener('click', () => {
      openModal('bannerModal');
    });

    bannerInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.type.startsWith('image/')) {
          currentBannerFile = file;
          const reader = new FileReader();
          reader.onload = function(e) {
            bannerPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          showToast('Please select an image file', 'error');
          this.value = '';
        }
      }
    });

    function saveBanner() {
      if (currentBannerFile) {
        const reader = new FileReader();
        reader.onload = function(e) {
          profileBanner.style.backgroundImage = `url(${e.target.result})`;
          closeModal('bannerModal');
          showToast('Banner updated successfully');
        };
        reader.readAsDataURL(currentBannerFile);
      } else {
        showToast('Please select an image to upload', 'error');
      }
    }

    // Content Upload Handling
    const contentInput = document.getElementById('contentInput');
    const contentPreview = document.getElementById('contentPreview');
    let currentContentFile = null;
    let contentCategories = new Set();

    contentInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.type.startsWith('image/')) {
          currentContentFile = file;
          const reader = new FileReader();
          reader.onload = function(e) {
            contentPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          showToast('Please select an image file', 'error');
          this.value = '';
        }
      }
    });

    function addContentCategory() {
      const input = document.getElementById('contentCategoryInput');
      const category = input.value.trim();
      if (category && !contentCategories.has(category)) {
        contentCategories.add(category);
        updateContentCategories();
        input.value = '';
      }
    }

    function removeContentCategory(category) {
      contentCategories.delete(category);
      updateContentCategories();
    }

    function updateContentCategories() {
      const container = document.getElementById('contentCategories');
      container.innerHTML = Array.from(contentCategories).map(category => `
        <div class="category-tag">
          ${category}
          <button type="button" onclick="removeContentCategory('${category}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      `).join('');
    }

    function saveContent(event) {
      event.preventDefault();
      if (!currentContentFile) {
        showToast('Please select an image', 'error');
        return false;
      }

      const title = document.getElementById('contentTitle').value;
      const description = document.getElementById('contentDescription').value;
      
      if (title && description) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const portfolioGrid = document.querySelector('.portfolio-grid');
          const newItem = document.createElement('div');
          newItem.className = 'portfolio-item';
          newItem.innerHTML = `
            <img src="${e.target.result}" alt="${title}" />
            <div class="portfolio-item-overlay">
              <div class="text-center">
                <h3 class="text-white mb-2">${title}</h3>
                <p class="text-white text-sm mb-4">${description}</p>
                <button class="button button-primary">View Details</button>
              </div>
            </div>
          `;
          portfolioGrid.insertBefore(newItem, portfolioGrid.firstChild);
          
          closeModal('contentModal');
          showToast('Content added successfully');
          
          // Reset form
          event.target.reset();
          contentPreview.src = '';
          contentCategories.clear();
          updateContentCategories();
          currentContentFile = null;
        };
        reader.readAsDataURL(currentContentFile);
      } else {
        showToast('Please fill in all required fields', 'error');
      }
      return false;
    }

    // Category Management
    let categories = new Set(['Fashion', 'Lifestyle', 'Beauty']); // Initial categories

    function addCategory() {
      const input = document.getElementById('categoryInput');
      const category = input.value.trim();
      if (category && !categories.has(category)) {
        categories.add(category);
        updateCategoryList();
        updateCategoryTags();
        input.value = '';
      }
    }

    function removeCategory(category) {
      categories.delete(category);
      updateCategoryList();
      updateCategoryTags();
    }

    function updateCategoryList() {
      const container = document.getElementById('categoryList');
      container.innerHTML = Array.from(categories).map(category => `
        <div class="category-item">
          <span>${category}</span>
          <button onclick="removeCategory('${category}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      `).join('');
    }

    function updateCategoryTags() {
      const container = document.querySelector('.category-tags');
      container.innerHTML = Array.from(categories).map(category => `
        <div class="category-tag">
          ${category}
          <button type="button" onclick="removeCategory('${category}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      `).join('');
    }

    // Initialize category list and tags
    updateCategoryList();
    updateCategoryTags();

    // Add event listeners for category input
    document.getElementById('categoryInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        addCategory();
      }
    });

    document.getElementById('contentCategoryInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        addContentCategory();
      }
    });

    // Update the "Add Category" button click handler
    document.querySelector('.profile-section-header .button-primary').onclick = function() {
      openModal('categoryModal');
    };

    // Update the "Add Content" button click handler
    document.querySelector('.portfolio-section .button-primary').onclick = function() {
      openModal('contentModal');
    };
  </script>
</body>

</html> 