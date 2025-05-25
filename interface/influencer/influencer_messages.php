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
  <title>Messages - InfluenceON</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="container navbar-content">
      <a href="influencer_dashboard.php" class="logo">InfluenceON</a>
      <div class="nav-links">
        <a href="influencer_dashboard.php" class="nav-link">Dashboard</a>
        <a href="influencer_campaigns.php" class="nav-link">Campaigns</a>
        <a href="influencer_analytics.php" class="nav-link">Analytics</a>
        <a href="influencer_messages.php" class="nav-link active">Messages</a>
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
          <h1 class="welcome-text">Messages</h1>
          <p class="text-secondary">Manage your conversations with brands and partners</p>
        </div>
        <button class="button button-primary">New Message</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-title">Unread Messages</div>
          <div class="stat-value">5</div>
          <div class="stat-change">New messages</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Active Chats</div>
          <div class="stat-value">8</div>
          <div class="stat-change">Ongoing conversations</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Response Rate</div>
          <div class="stat-value">95%</div>
          <div class="stat-change positive">Within 24 hours</div>
        </div>
        <div class="stat-card">
          <div class="stat-title">Campaign Inquiries</div>
          <div class="stat-value">12</div>
          <div class="stat-change positive">New opportunities</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Recent Messages</h2>
              <div class="flex gap-2">
                <button class="button button-primary">Filter</button>
                <button class="button">Mark All Read</button>
              </div>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=7" alt="Brand logo" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Fashion Brand X</div>
                  <div class="list-item-subtitle">Campaign brief for summer collection...</div>
                </div>
                <div class="flex gap-4">
                  <span class="badge badge-warning">Unread</span>
                  <div class="text-sm text-secondary">2h ago</div>
                </div>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=8" alt="Brand logo" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Tech Company Y</div>
                  <div class="list-item-subtitle">Following up on the product review...</div>
                </div>
                <div class="flex gap-4">
                  <div class="text-sm text-secondary">5h ago</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Campaign Discussions</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=9" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Summer Collection Launch</div>
                  <div class="list-item-subtitle">3 participants</div>
                </div>
                <button class="button">Join Chat</button>
              </div>
              <div class="list-item">
                <img src="https://i.pravatar.cc/150?img=10" alt="Campaign thumbnail" class="avatar" />
                <div class="list-item-content">
                  <div class="list-item-title">Tech Review Series</div>
                  <div class="list-item-subtitle">5 participants</div>
                </div>
                <button class="button">Join Chat</button>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Templates</div>
                  <div class="list-item-subtitle">Manage response templates</div>
                </div>
                <button class="button">View</button>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Archive</div>
                  <div class="list-item-subtitle">View archived messages</div>
                </div>
                <button class="button">Open</button>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Message Settings</h2>
            </div>
            <div class="list">
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Auto-Reply</div>
                  <div class="list-item-subtitle">Manage automatic responses</div>
                </div>
                <span class="badge badge-success">Active</span>
              </div>
              <div class="list-item">
                <div class="list-item-content">
                  <div class="list-item-title">Notifications</div>
                  <div class="list-item-subtitle">Email and push settings</div>
                </div>
                <span class="badge badge-success">Enabled</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../js/script.js"></script>
</body>

</html> 