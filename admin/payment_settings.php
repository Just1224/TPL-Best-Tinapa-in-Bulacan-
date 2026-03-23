<?php
@include '../includes/config.php';
@include 'includes/auth.php';

$message = [];

// Handle settings update
if(isset($_POST['update_payment_settings'])){
    $bdo_account = $_POST['bdo_account'] ?? '';
    $gcash_number = $_POST['gcash_number'] ?? '';
    $maya_number = $_POST['maya_number'] ?? '';
    $bank_email = $_POST['bank_email'] ?? '';
    $delivery_days = $_POST['delivery_days'] ?? '1-3';

    // In a real system, you'd save these to a settings table
    // For now, we'll just show a success message
    $message[] = 'success:Payment settings updated successfully!';
}

// Get current settings (would normally come from database)
$settings = [
    'bdo_account' => '123-456-789-123',
    'gcash_number' => '09171234567',
    'maya_number' => '09181234567',
    'bank_email' => 'tinapa@example.com',
    'delivery_days' => '1-3'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment Settings</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: var(--light-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-container {
            flex: 1;
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .admin-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .admin-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .payment-method-group {
            background: var(--light-color);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .payment-method-group h3 {
            color: var(--primary-color);
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(196, 30, 58, 0.3);
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--text-color);
            margin-top: 5px;
            font-style: italic;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert.info {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
            border-left: 4px solid #2980b9;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.3);
        }

        .payment-preview {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .payment-preview strong {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
            }

            .payment-method-group {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<?php @include 'header_admin.php'; ?>

<div class="admin-container">
    <div class="admin-section">
        <h2><i class="fas fa-credit-card"></i> Payment Settings</h2>

        <?php
        foreach($message as $msg){
            if(strpos($msg, 'success:') === 0){
                echo '<div class="alert success"><i class="fas fa-check"></i> ' . substr($msg, 8) . '</div>';
            }
        }
        ?>

        <div class="alert info">
            <i class="fas fa-info-circle"></i> Configure your payment account details below. These will be displayed to customers during checkout and on order confirmations.
        </div>

        <form method="POST">
            <!-- Cash on Delivery -->
            <div class="payment-method-group">
                <h3><i class="fas fa-money-bill-wave"></i> Cash on Delivery (COD)</h3>
                <div class="form-group">
                    <label>Delivery Time</label>
                    <input type="text" name="delivery_days" value="<?php echo htmlspecialchars($settings['delivery_days']); ?>" placeholder="e.g., 1-3 business days">
                    <p class="help-text">Specify delivery timeframe for COD orders</p>
                </div>
            </div>

            <!-- Bank Transfer -->
            <div class="payment-method-group">
                <h3><i class="fas fa-university"></i> Bank Transfer</h3>
                <div class="form-group">
                    <label>BDO Account Number *</label>
                    <input type="text" name="bdo_account" value="<?php echo htmlspecialchars($settings['bdo_account']); ?>" placeholder="Your BDO account number" required>
                    <p class="help-text">Format: XXX-XXX-XXX-XXX (e.g., 123-456-789-123)</p>
                </div>
                <div class="form-group">
                    <label>Email for Bank Receipts</label>
                    <input type="email" name="bank_email" value="<?php echo htmlspecialchars($settings['bank_email']); ?>" placeholder="tinapa@example.com">
                    <p class="help-text">Email where customers should send proof of bank transfer</p>
                </div>
                <div class="payment-preview">
                    <strong>Preview:</strong><br>
                    Bank: BDO Unibank<br>
                    Account: <?php echo htmlspecialchars($settings['bdo_account']); ?><br>
                    Customers can also use other banks with InstaPay/PesoNet
                </div>
            </div>

            <!-- GCash -->
            <div class="payment-method-group">
                <h3><i class="fas fa-mobile-alt"></i> GCash</h3>
                <div class="form-group">
                    <label>GCash Number *</label>
                    <input type="tel" name="gcash_number" value="<?php echo htmlspecialchars($settings['gcash_number']); ?>" placeholder="09XXXXXXXXX" required>
                    <p class="help-text">Your registered GCash number (format: 09XXXXXXXXX)</p>
                </div>
                <div class="payment-preview">
                    <strong>Preview:</strong><br>
                    Customers will be shown step-by-step instructions to send money to: <?php echo htmlspecialchars($settings['gcash_number']); ?><br>
                    They'll also receive instructions to send receipt via email
                </div>
            </div>

            <!-- Maya -->
            <div class="payment-method-group">
                <h3><i class="fas fa-digital-tachograph"></i> Maya</h3>
                <div class="form-group">
                    <label>Maya Account Number *</label>
                    <input type="tel" name="maya_number" value="<?php echo htmlspecialchars($settings['maya_number']); ?>" placeholder="09XXXXXXXXX" required>
                    <p class="help-text">Your registered Maya account number (format: 09XXXXXXXXX)</p>
                </div>
                <div class="payment-preview">
                    <strong>Preview:</strong><br>
                    Customers will receive detailed instructions to send money via Maya app to: <?php echo htmlspecialchars($settings['maya_number']); ?><br>
                    They must include order number and send receipt proof
                </div>
            </div>

            <!-- Credit Card Installment -->
            <div class="payment-method-group">
                <h3><i class="fas fa-credit-card"></i> Credit Card Installment (0%)</h3>
                <p style="color: var(--text-color); margin: 0;">Installment available in 3, 6, and 12-month plans. Our team will contact customers to arrange details via their bank's partner merchants or our payment gateway.</p>
            </div>

            <button type="submit" name="update_payment_settings" class="btn-save">
                <i class="fas fa-save"></i> Save Payment Settings
            </button>
        </form>
    </div>

    <!-- Payment Methods Summary -->
    <div class="admin-section">
        <h2><i class="fas fa-list-check"></i> Available Payment Methods</h2>
        <p style="color: var(--text-color);">The following payment methods are currently active for your customers:</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
            <div style="padding: 15px; background: var(--light-color); border-radius: 4px;">
                <strong><i class="fas fa-money-bill-wave"></i> Cash on Delivery</strong>
                <p style="margin: 10px 0 0 0; color: var(--text-color); font-size: 0.9rem;">✓ Active - Pay upon delivery</p>
            </div>
            <div style="padding: 15px; background: var(--light-color); border-radius: 4px;">
                <strong><i class="fas fa-mobile-alt"></i> GCash</strong>
                <p style="margin: 10px 0 0 0; color: var(--text-color); font-size: 0.9rem;">✓ Active - Digital wallet</p>
            </div>
            <div style="padding: 15px; background: var(--light-color); border-radius: 4px;">
                <strong><i class="fas fa-digital-tachograph"></i> Maya</strong>
                <p style="margin: 10px 0 0 0; color: var(--text-color); font-size: 0.9rem;">✓ Active - Digital wallet</p>
            </div>
            <div style="padding: 15px; background: var(--light-color); border-radius: 4px;">
                <strong><i class="fas fa-university"></i> Bank Transfer</strong>
                <p style="margin: 10px 0 0 0; color: var(--text-color); font-size: 0.9rem;">✓ Active - InstaPay/PesoNet</p>
            </div>
            <div style="padding: 15px; background: var(--light-color); border-radius: 4px;">
                <strong><i class="fas fa-credit-card"></i> Installment</strong>
                <p style="margin: 10px 0 0 0; color: var(--text-color); font-size: 0.9rem;">✓ Active - 3/6/12 months 0%</p>
            </div>
        </div>
    </div>
</div>

<?php @include 'footer_admin.php'; ?>

</body>
</html>
