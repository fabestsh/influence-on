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
    <title>Process Payment - InfluenceON</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .payment-processor {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .payment-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .payment-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .payment-card {
            background: var(--bg-white);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .payment-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .summary-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .summary-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .summary-value.amount {
            color: var(--primary);
        }

        .payment-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-section {
            display: grid;
            gap: 1rem;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
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

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .payment-method {
            position: relative;
            border: 2px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.5rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-method:hover {
            border-color: var(--primary);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .payment-method input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .payment-method-icon {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .payment-method-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary);
        }

        .payment-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(99, 102, 241, 0.1);
        }

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .secure-badge svg {
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
                <a href="campaigns.php" class="nav-link active">Campaigns</a>
                <a href="influencers.php" class="nav-link">Influencers</a>
                <a href="analytics.php" class="nav-link">Analytics</a>
                <form method="POST" action="../../interface/auth/php/logout.php">
                    <button type="submit" class="button button-primary">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="payment-processor">
        <div class="payment-header">
            <h1 class="payment-title">Process Payment</h1>
            <p class="payment-subtitle">Summer Collection Launch 2024</p>
        </div>

        <div class="payment-card">
            <div class="payment-summary">
                <div class="summary-item">
                    <div class="summary-label">Payment Amount</div>
                    <div class="summary-value amount">$12,500</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Payment Type</div>
                    <div class="summary-value">Initial Payment (50%)</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Due Date</div>
                    <div class="summary-value">June 1, 2024</div>
                </div>
            </div>

            <form class="payment-form">
                <div class="form-section">
                    <h3 class="form-section-title">Select Payment Method</h3>
                    <div class="payment-methods">
                        <label class="payment-method selected">
                            <input type="radio" name="payment_method" value="bank_transfer" checked>
                            <div class="payment-method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                                Bank Transfer
                            </div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div class="payment-method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Credit Card
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Payment Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Account Holder</label>
                            <input type="text" class="form-input" value="Sarah Johnson" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Account</label>
                            <input type="text" class="form-input" value="**** **** **** 1234" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-input" value="Example Bank" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Routing Number</label>
                            <input type="text" class="form-input" value="******1234" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Payment Notes</h3>
                    <div class="form-group">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-input" placeholder="Enter payment reference number">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-input" rows="3" placeholder="Add any additional notes about the payment"></textarea>
                    </div>
                </div>

                <div class="payment-actions">
                    <button type="button" class="button" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="button button-primary">Confirm Payment</button>
                </div>

                <div class="secure-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Secure Payment Processing
                </div>
            </form>
        </div>
    </main>

    <script>
        // Handle payment method selection
        document.querySelectorAll('.payment-method input[type="radio"]').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.classList.remove('selected');
                });
                this.closest('.payment-method').classList.add('selected');
            });
        });

        // Handle form submission
        document.querySelector('.payment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add payment processing logic here
            alert('Payment processed successfully!');
            window.location.href = 'campaigns.php';
        });
    </script>
</body>
</html> 