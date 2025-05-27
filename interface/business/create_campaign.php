<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['user_role'] !== 'business' || $_SESSION['status'] != 1) {
  header('Location: ../auth/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Campaign - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <link rel="stylesheet" href="../../assets/css/form-controls.css" />
  <style>
    .campaign-form {
      max-width: 800px;
      margin: 0 auto;
    }

    .form-section {
      background: var(--bg-white);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
    }

    .form-section-header {
      margin-bottom: 1.5rem;
    }

    .form-section-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-section-description {
      color: var(--text-secondary);
      font-size: 0.875rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
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

    .form-select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background: var(--bg-white);
      color: var(--text-primary);
      font-size: 0.875rem;
      cursor: pointer;
    }

    .requirement-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }

    .requirement-tag {
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

    .requirement-tag.active {
      background: var(--primary);
      color: white;
    }

    .requirement-tag:hover {
      background-color: var(--bg-hover);
    }

    .requirement-tag.active:hover {
      background-color: var(--primary-dark);
    }

    .influencer-requirements {
      margin-top: 1rem;
    }

    .requirement-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .requirement-item input[type="number"] {
      width: 100px;
    }

    .requirement-item button {
      color: var(--danger);
      opacity: 0.5;
      transition: opacity 0.2s ease;
    }

    .requirement-item button:hover {
      opacity: 1;
    }

    .budget-input {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .budget-input input {
      flex: 1;
    }

    .budget-input select {
      width: 100px;
    }

    .target-audience-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }

    .audience-tag {
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

    .audience-tag.active {
      background: var(--primary);
      color: white;
    }

    .audience-tag:hover {
      background-color: var(--bg-hover);
    }

    .audience-tag.active:hover {
      background-color: var(--primary-dark);
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

    .campaign-goals {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }

    .goal-card {
      padding: 1rem;
      background: var(--bg-light);
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .goal-card:hover {
      background: var(--bg-hover);
    }

    .goal-card.active {
      background: var(--primary);
      color: white;
    }

    .goal-card.active:hover {
      background: var(--primary-dark);
    }

    .goal-card-title {
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .goal-card-description {
      font-size: 0.875rem;
      opacity: 0.8;
    }

    .deliverable-item {
      background: var(--bg-light);
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .deliverable-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .deliverable-title {
      font-weight: 500;
      color: var(--text-primary);
    }

    .remove-deliverable {
      color: var(--danger);
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.25rem;
      transition: color 0.2s ease;
    }

    .remove-deliverable:hover {
      color: var(--danger-dark);
    }

    .add-deliverable {
      width: 100%;
      padding: 0.75rem;
      background: var(--bg-light);
      border: 2px dashed var(--border-color);
      border-radius: 0.5rem;
      color: var(--text-secondary);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .add-deliverable:hover {
      border-color: var(--primary);
      color: var(--primary);
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="#" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="business_dashboard.php" class="nav-link">Dashboard</a>
        <a href="business_campaigns.php" class="nav-link">Campaigns</a>
        <a href="business_analytics.php" class="nav-link">Analytics</a>
        <a href="business_messages.php" class="nav-link">Messages</a>
        <a href="business_profile.php" class="nav-link">Profile</a>
        <form method="POST" action="../../interface/auth/php/logout.php">
          <button type="submit" class="button button-primary">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="dashboard">
    <div class="container">
      <div class="campaign-form">
        <div class="form-section">
          <div class="form-section-header">
            <h1 class="form-section-title">Create New Campaign</h1>
            <p class="form-section-description">Fill in the details below to create a new marketing campaign</p>
          </div>

          <form id="campaignForm" onsubmit="return submitCampaign(event)">
            <!-- Campaign Overview -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Campaign Overview</h2>
                <p class="form-section-description">Basic information about your campaign</p>
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Campaign Title</label>
                  <input type="text" class="form-input" name="title" required placeholder="e.g., Summer Collection Launch" />
                </div>
                <div class="form-group">
                  <label class="form-label">Category</label>
                  <select class="form-select" name="category" required>
                    <option value="">Select a category</option>
                    <option value="fashion">Fashion</option>
                    <option value="beauty">Beauty</option>
                    <option value="lifestyle">Lifestyle</option>
                    <option value="tech">Technology</option>
                    <option value="food">Food & Dining</option>
                    <option value="travel">Travel</option>
                    <option value="fitness">Fitness & Health</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div class="form-group col-span-2">
                  <label class="form-label">Campaign Description</label>
                  <textarea class="form-input form-textarea" name="description" required placeholder="Describe your campaign goals and requirements..."></textarea>
                </div>
              </div>
            </div>

            <!-- Campaign Goals -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Campaign Goals</h2>
                <p class="form-section-description">Select the primary goals for your campaign</p>
              </div>
              <div class="campaign-goals">
                <div class="goal-card" data-goal="awareness">
                  <div class="goal-card-title">Brand Awareness</div>
                  <div class="goal-card-description">Increase brand visibility and recognition</div>
                </div>
                <div class="goal-card" data-goal="engagement">
                  <div class="goal-card-title">Engagement</div>
                  <div class="goal-card-description">Drive likes, comments, and shares</div>
                </div>
                <div class="goal-card" data-goal="conversion">
                  <div class="goal-card-title">Conversions</div>
                  <div class="goal-card-description">Generate leads and sales</div>
                </div>
                <div class="goal-card" data-goal="reach">
                  <div class="goal-card-title">Reach</div>
                  <div class="goal-card-description">Expand audience reach</div>
                </div>
              </div>
            </div>

            <!-- Target Audience -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Target Audience</h2>
                <p class="form-section-description">Define your target audience demographics</p>
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Age Range</label>
                  <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                    <input type="number" class="form-input" name="ageMin" min="13" max="65" placeholder="Min" required />
                    <input type="number" class="form-input" name="ageMax" min="13" max="65" placeholder="Max" required />
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Gender</label>
                  <div class="target-audience-tags">
                    <div class="audience-tag active" data-gender="all">All</div>
                    <div class="audience-tag" data-gender="male">Male</div>
                    <div class="audience-tag" data-gender="female">Female</div>
                    <div class="audience-tag" data-gender="other">Other</div>
                  </div>
                </div>
                <div class="form-group col-span-2">
                  <label class="form-label">Interests</label>
                  <div class="target-audience-tags">
                    <div class="audience-tag" data-interest="fashion">Fashion</div>
                    <div class="audience-tag" data-interest="beauty">Beauty</div>
                    <div class="audience-tag" data-interest="tech">Technology</div>
                    <div class="audience-tag" data-interest="travel">Travel</div>
                    <div class="audience-tag" data-interest="fitness">Fitness</div>
                    <div class="audience-tag" data-interest="food">Food</div>
                    <div class="audience-tag" data-interest="lifestyle">Lifestyle</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Influencer Requirements -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Influencer Requirements</h2>
                <p class="form-section-description">Specify your requirements for influencers</p>
              </div>
              <div class="form-group">
                <label class="form-label">Platform Requirements</label>
                <div class="requirement-tags">
                  <div class="requirement-tag active" data-platform="instagram">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0z"/>
                    </svg>
                    Instagram
                  </div>
                  <div class="requirement-tag" data-platform="tiktok">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                    </svg>
                    TikTok
                  </div>
                  <div class="requirement-tag" data-platform="youtube">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    YouTube
                  </div>
                  <div class="requirement-tag" data-platform="twitter">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                  </div>
                </div>
              </div>
              <div id="requirementsList" class="influencer-requirements">
                <!-- Requirements will be added here dynamically -->
              </div>
              <button type="button" class="button" onclick="addRequirement()">Add Requirement</button>
            </div>

            <!-- Budget -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Budget</h2>
                <p class="form-section-description">Set your campaign budget</p>
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Budget Range</label>
                  <div class="budget-input">
                    <input type="number" class="form-input" name="budgetMin" required min="0" step="0.01" placeholder="Min" />
                    <span>-</span>
                    <input type="number" class="form-input" name="budgetMax" required min="0" step="0.01" placeholder="Max" />
                    <select class="form-select" name="currency" required>
                      <option value="USD">USD</option>
                      <option value="EUR">EUR</option>
                      <option value="GBP">GBP</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Payment Terms</label>
                  <select class="form-select" name="paymentTerms" required>
                    <option value="">Select payment terms</option>
                    <option value="upfront">Upfront Payment</option>
                    <option value="milestone">Milestone-based</option>
                    <option value="completion">Upon Completion</option>
                    <option value="negotiable">Negotiable</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Timeline -->
            <div class="form-section">
              <div class="form-section-header">
                <h2 class="form-section-title">Timeline</h2>
                <p class="form-section-description">Set campaign duration and deadlines</p>
              </div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Campaign Start Date</label>
                  <input type="date" class="form-input" name="startDate" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Campaign End Date</label>
                  <input type="date" class="form-input" name="endDate" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Application Deadline</label>
                  <input type="date" class="form-input" name="applicationDeadline" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Content Submission Deadline</label>
                  <input type="date" class="form-input" name="contentDeadline" required />
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
              <button type="button" class="button" onclick="window.location.href='business_campaigns.php'">Cancel</button>
              <button type="submit" class="button button-primary">Create Campaign</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Campaign Goals Selection
    const goalCards = document.querySelectorAll('.goal-card');
    goalCards.forEach(card => {
      card.addEventListener('click', function() {
        this.classList.toggle('active');
      });
    });

    // Platform Requirements Selection
    const platformTags = document.querySelectorAll('.requirement-tag');
    platformTags.forEach(tag => {
      tag.addEventListener('click', function() {
        this.classList.toggle('active');
      });
    });

    // Target Audience Selection
    const audienceTags = document.querySelectorAll('.audience-tag');
    audienceTags.forEach(tag => {
      tag.addEventListener('click', function() {
        if (this.dataset.gender === 'all') {
          // If "All" is selected, deselect other gender options
          document.querySelectorAll('.audience-tag[data-gender]').forEach(t => {
            if (t !== this) t.classList.remove('active');
          });
        } else {
          // If specific gender is selected, deselect "All"
          document.querySelector('.audience-tag[data-gender="all"]').classList.remove('active');
        }
        this.classList.toggle('active');
      });
    });

    // Requirements Management
    let requirementCount = 0;

    function addRequirement() {
      const requirementsList = document.getElementById('requirementsList');
      const requirementItem = document.createElement('div');
      requirementItem.className = 'requirement-item';
      requirementItem.innerHTML = `
        <div class="flex-1">
          <select class="form-select" name="requirements[${requirementCount}][type]" required>
            <option value="">Select requirement type</option>
            <option value="followers">Minimum Followers</option>
            <option value="engagement">Minimum Engagement Rate</option>
            <option value="posts">Number of Posts</option>
            <option value="stories">Number of Stories</option>
            <option value="videos">Number of Videos</option>
          </select>
        </div>
        <div class="flex items-center gap-2">
          <input type="number" class="form-input" name="requirements[${requirementCount}][value]" placeholder="Value" min="0" step="0.01" required />
          <button type="button" class="button" onclick="this.closest('.requirement-item').remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      `;
      requirementsList.appendChild(requirementItem);
      requirementCount++;
    }

    // Form Submission
    function submitCampaign(event) {
      event.preventDefault();
      const form = event.target;
      const formData = new FormData(form);

      // Get selected goals
      const goals = Array.from(document.querySelectorAll('.goal-card.active'))
        .map(card => card.dataset.goal);
      formData.append('goals', JSON.stringify(goals));

      // Get selected platforms
      const platforms = Array.from(document.querySelectorAll('.requirement-tag.active'))
        .map(tag => tag.dataset.platform);
      formData.append('platforms', JSON.stringify(platforms));

      // Get selected audience
      const audience = {
        gender: Array.from(document.querySelectorAll('.audience-tag[data-gender].active'))
          .map(tag => tag.dataset.gender),
        interests: Array.from(document.querySelectorAll('.audience-tag[data-interest].active'))
          .map(tag => tag.dataset.interest)
      };
      formData.append('audience', JSON.stringify(audience));

      // Validate dates
      const startDate = new Date(formData.get('startDate'));
      const endDate = new Date(formData.get('endDate'));
      const applicationDeadline = new Date(formData.get('applicationDeadline'));
      const contentDeadline = new Date(formData.get('contentDeadline'));

      if (endDate <= startDate) {
        showToast('End date must be after start date', 'error');
        return false;
      }

      if (applicationDeadline >= startDate) {
        showToast('Application deadline must be before campaign start date', 'error');
        return false;
      }

      if (contentDeadline > endDate) {
        showToast('Content submission deadline must be before campaign end date', 'error');
        return false;
      }

      // Validate budget
      const budgetMin = parseFloat(formData.get('budgetMin'));
      const budgetMax = parseFloat(formData.get('budgetMax'));
      if (budgetMax <= budgetMin) {
        showToast('Maximum budget must be greater than minimum budget', 'error');
        return false;
      }

      // Simulate form submission
      setTimeout(() => {
        showToast('Campaign created successfully');
        window.location.href = 'business_campaigns.php';
      }, 1000);

      return false;
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

    // Initialize with one requirement
    addRequirement();

    // Date validation
    const startDateInput = document.querySelector('input[name="startDate"]');
    const endDateInput = document.querySelector('input[name="endDate"]');
    const applicationDeadlineInput = document.querySelector('input[name="applicationDeadline"]');
    const contentDeadlineInput = document.querySelector('input[name="contentDeadline"]');
    const today = new Date().toISOString().split('T')[0];

    startDateInput.min = today;
    startDateInput.addEventListener('change', function() {
      endDateInput.min = this.value;
      applicationDeadlineInput.max = this.value;
      if (endDateInput.value && endDateInput.value < this.value) {
        endDateInput.value = this.value;
      }
      if (applicationDeadlineInput.value && applicationDeadlineInput.value > this.value) {
        applicationDeadlineInput.value = this.value;
      }
    });

    endDateInput.addEventListener('change', function() {
      contentDeadlineInput.max = this.value;
      if (contentDeadlineInput.value && contentDeadlineInput.value > this.value) {
        contentDeadlineInput.value = this.value;
      }
    });

    applicationDeadlineInput.max = startDateInput.value;
    contentDeadlineInput.max = endDateInput.value;
  </script>
</body>

</html> 