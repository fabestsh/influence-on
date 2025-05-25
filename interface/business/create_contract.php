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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Contract - InfluenceON</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .contract-editor {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .contract-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .contract-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .contract-actions {
            display: flex;
            gap: 1rem;
        }

        .contract-content {
            background: var(--bg-white);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .contract-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(99, 102, 241, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-input {
            padding: 0.75rem;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .terms-section {
            background: var(--bg-light);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .terms-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .terms-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .terms-item:last-child {
            border-bottom: none;
        }

        .terms-checkbox {
            margin-top: 0.25rem;
        }

        .terms-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(99, 102, 241, 0.1);
        }

        .preview-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            background: var(--bg-light);
            color: var(--text-primary);
            border: 1px solid rgba(99, 102, 241, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .preview-button:hover {
            background: rgba(99, 102, 241, 0.05);
            border-color: var(--primary);
        }

        .export-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .export-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
        }

        /* PDF Preview Modal */
        .preview-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .preview-modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .preview-content {
            background: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
        }

        .preview-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .preview-close:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .preview-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(99, 102, 241, 0.1);
        }

        .preview-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .preview-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
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
                <form method="POST" action="../../interface/auth/php/logout.php">
                    <button type="submit" class="button button-primary">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="contract-editor">
        <div class="contract-header">
            <h1 class="contract-title">Campaign Contract</h1>
            <div class="contract-actions">
                <button class="preview-button" onclick="openPreview()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Preview Contract
                </button>
                <button class="export-button" onclick="exportPDF()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export as PDF
                </button>
            </div>
        </div>

        <div class="contract-content" id="contractContent">
            <div class="contract-section">
                <h2 class="section-title">Campaign Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Campaign Title</label>
                        <input type="text" class="form-input" value="Summer Collection Launch 2024" >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Campaign Duration</label>
                        <input type="text" class="form-input" value="June 1, 2024 - July 31, 2024" >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Campaign Budget</label>
                        <input type="text" class="form-input" value="$25,000" >
                    </div>
                </div>
            </div>

            <div class="contract-section">
                <h2 class="section-title">Contract Details</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Influencer Name</label>
                        <input type="text" class="form-input" value="Sarah Johnson" >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Platforms</label>
                        <input type="text" class="form-input" value="Instagram, TikTok" >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Terms</label>
                        <input type="text" class="form-input" value="50% upfront, 50% upon completion" >
                    </div>
                </div>
            </div>

            <div class="contract-section">
                <h2 class="section-title">Deliverables</h2>
                <div class="form-group">
                    <label class="form-label">Content Requirements</label>
                    <div class="form-input" style="background: var(--bg-light); min-height: 100px; white-space: pre-line;">
• 4 Instagram Posts (2 Feed, 2 Stories)
• 2 TikTok Videos (15-30 seconds)
• All content must feature the new summer collection
• Include brand hashtags: #SummerStyle2024 #InfluenceON
• Tag @brandname in all posts
• Maintain brand voice and aesthetic
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Posting Schedule</label>
                    <div class="form-input" style="background: var(--bg-light); min-height: 100px; white-space: pre-line;">
Week 1:
- Instagram Feed Post (June 5)
- TikTok Video (June 7)

Week 2:
- Instagram Stories (June 12)
- Instagram Feed Post (June 14)

Week 3:
- TikTok Video (June 21)
- Instagram Stories (June 23)

All content must be submitted for approval 48 hours before posting.
                    </div>
                </div>
            </div>

            <div class="contract-section">
                <h2 class="section-title">Terms and Conditions</h2>
                <div class="terms-section">
                    <ul class="terms-list">
                        <li class="terms-item">
                            <input type="checkbox" class="terms-checkbox" checked disabled>
                            <span class="terms-text">The influencer agrees to create and post content according to the specified schedule and requirements.</span>
                        </li>
                        <li class="terms-item">
                            <input type="checkbox" class="terms-checkbox" checked disabled>
                            <span class="terms-text">All content must be approved by the business before posting.</span>
                        </li>
                        <li class="terms-item">
                            <input type="checkbox" class="terms-checkbox" checked disabled>
                            <span class="terms-text">The influencer will maintain professional conduct and brand alignment throughout the campaign.</span>
                        </li>
                        <li class="terms-item">
                            <input type="checkbox" class="terms-checkbox" checked disabled>
                            <span class="terms-text">Payment will be processed according to the selected payment terms.</span>
                        </li>
                        <li class="terms-item">
                            <input type="checkbox" class="terms-checkbox" checked disabled>
                            <span class="terms-text">The influencer will provide access to post analytics and engagement metrics.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="contract-section">
                <h2 class="section-title">Signatures</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Business Representative</label>
                        <div class="form-input" style="background: var(--bg-light);">
                            John Smith
                            Marketing Director
                            InfluenceON
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Influencer</label>
                        <div class="form-input" style="background: var(--bg-light);">
                            Sarah Johnson
                            Content Creator
                            @sarahjohnson
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button class="button" onclick="window.history.back()">Back to Campaigns</button>
                <button class="button button-primary" onclick="exportPDF()">Download Contract</button>
            </div>
        </div>
    </main>

    <!-- PDF Preview Modal -->
    <div class="preview-modal" id="previewModal">
        <div class="preview-content">
            <button class="preview-close" onclick="closePreview()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="preview-header">
                <h1 class="preview-title">Campaign Contract</h1>
                <p class="preview-subtitle">Summer Collection Launch 2024</p>
            </div>
            <div id="previewContent">
                <!-- Preview content will be populated here -->
            </div>
        </div>
    </div>

    <script>
        function openPreview() {
            const modal = document.getElementById('previewModal');
            const previewContent = document.getElementById('previewContent');
            const contractContent = document.getElementById('contractContent').cloneNode(true);
            
            // Remove action buttons and form elements from preview
            contractContent.querySelectorAll('.contract-actions, .button-group, .terms-checkbox').forEach(el => el.remove());
            
            previewContent.innerHTML = '';
            previewContent.appendChild(contractContent);
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            document.getElementById('previewModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function exportPDF() {
            const element = document.getElementById('contractContent');
            const opt = {
                margin: 1,
                filename: 'campaign-contract.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Remove action buttons before export
            const buttons = element.querySelectorAll('.contract-actions, .button-group, .terms-checkbox');
            buttons.forEach(button => button.style.display = 'none');

            html2pdf().set(opt).from(element).save().then(() => {
                // Restore buttons after export
                buttons.forEach(button => button.style.display = '');
            });
        }

        // Close preview modal when clicking outside
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });

        // Close preview with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });
    </script>
</body>
</html> 