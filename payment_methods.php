<?php
@include 'includes/config.php';
session_start();
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods - PTL Best Tinapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-guide-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .guide-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 40px;
        }

        .guide-header h1 {
            color: white;
            margin: 0;
            font-size: 2.5rem;
        }

        .guide-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
        }

        .payment-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-color);
        }

        .payment-card h2 {
            color: var(--primary-color);
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.8rem;
        }

        .payment-card p {
            color: var(--text-color);
            line-height: 1.8;
        }

        .steps {
            background: var(--light-color);
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .step {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            background: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-content strong {
            color: var(--dark-color);
            display: block;
            margin-bottom: 5px;
        }

        .highlights {
            background: rgba(196, 30, 58, 0.1);
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid var(--primary-color);
            margin: 15px 0;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }

        .comparison-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .comparison-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .comparison-table tbody tr:hover {
            background: var(--light-color);
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-instant {
            background: rgba(39, 174, 96, 0.2);
            color: #155724;
        }

        .badge-fast {
            background: rgba(52, 152, 219, 0.2);
            color: #0c5460;
        }

        .badge-flexible {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .faq-section {
            margin-top: 40px;
        }

        .faq-item {
            background: white;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
        }

        .faq-question {
            color: var(--primary-color);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .faq-answer {
            color: var(--text-color);
            margin-top: 10px;
            line-height: 1.8;
        }

        .contact-section {
            background: var(--light-color);
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-top: 40px;
        }

        .contact-section h3 {
            color: var(--primary-color);
            margin-top: 0;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .contact-item {
            background: white;
            padding: 15px;
            border-radius: 4px;
        }

        .contact-item i {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .guide-header h1 {
                font-size: 1.8rem;
            }

            .payment-card {
                padding: 20px;
            }

            .comparison-table {
                font-size: 0.9rem;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="payment-guide-container">
    <div class="guide-header">
        <h1><i class="fas fa-credit-card"></i> Payment Methods</h1>
        <p>Choose the most convenient way to pay for your order</p>
    </div>

    <!-- Cash on Delivery -->
    <div class="payment-card">
        <h2><i class="fas fa-money-bill-wave"></i> Cash on Delivery (COD)</h2>
        <p>The simplest way to pay - just give the exact amount to our delivery driver when your order arrives.</p>
        
        <div class="highlights">
            <strong><i class="fas fa-check"></i> Best for:</strong> Anyone who prefers to pay in cash and see their order before payment
        </div>

        <strong>Steps:</strong>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Place Your Order</strong>
                    Select COD at checkout
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Wait for Delivery</strong>
                    Order arrives in 1-3 business days
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Pay to Driver</strong>
                    Give exact payment to delivery personnel
                </div>
            </div>
        </div>

        <p><strong>Advantages:</strong> No prepayment needed, inspect before paying, safest for buyers</p>
    </div>

    <!-- GCash -->
    <div class="payment-card">
        <h2><i class="fas fa-mobile-alt"></i> GCash</h2>
        <p>One of the most popular digital wallets in the Philippines. Instant transfer with your GCash app.</p>
        
        <div class="highlights">
            <strong><i class="fas fa-bolt"></i> Fastest payment method</strong> - Instant transfer, quickest order processing
        </div>

        <strong>Steps:</strong>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Open GCash App</strong>
                    Make sure you have sufficient balance
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Tap Send Money</strong>
                    Choose "Send to GCash User"
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Enter Details</strong>
                    Our GCash number and exact amount
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Screenshot & Send</strong>
                    Take screenshot of confirmation
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <strong>Share Receipt</strong>
                    Send screenshot via email or messenger
                </div>
            </div>
        </div>

        <p><strong>Advantages:</strong> Instant, no transaction fees (for senders), immediate order confirmation, available 24/7</p>
    </div>

    <!-- Maya -->
    <div class="payment-card">
        <h2><i class="fas fa-digital-tachograph"></i> Maya (formerly PayMaya)</h2>
        <p>Safe and secure digital wallet with rewards and cashback programs.</p>
        
        <div class="highlights">
            <strong><i class="fas fa-gift"></i> Earn Points</strong> - Get rewards and cashback on every transaction
        </div>

        <strong>Steps:</strong>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Open Maya App</strong>
                    Login to your Maya account
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Select Send Money</strong>
                    Choose "Send to Maya Account"
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Enter Our Details</strong>
                    Maya number and payment amount
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Confirm Payment</strong>
                    Review and approve the transaction
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <strong>Screenshot Receipt</strong>
                    Send confirmation screenshot via email
                </div>
            </div>
        </div>

        <p><strong>Advantages:</strong> Rewards program, secure, linked to credit cards, online shopping protection</p>
    </div>

    <!-- Bank Transfer -->
    <div class="payment-card">
        <h2><i class="fas fa-university"></i> Bank Transfer</h2>
        <p>Transfer directly from your bank account using InstaPay or PesoNet. Works with all Philippine banks.</p>
        
        <div class="highlights">
            <strong><i class="fas fa-check"></i> Available in:</strong> BDO, BPI, Metrobank, BDO Network Online, and other partner banks
        </div>

        <strong>How to Transfer:</strong>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <strong>Access Your Personal Banking</strong>
                    BDO Online, BPI Online, or your bank's app
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <strong>Choose Transfer Option</strong>
                    Select InstaPay or PesoNet
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <strong>Enter Recipient Details</strong>
                    Our BDO account number and name
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <strong>Enter Amount & Reference</strong>
                    Use your order number as reference
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <strong>Send Receipt</strong>
                    Email the bank receipt screenshot to us
                </div>
            </div>
        </div>

        <p><strong>Advantages:</strong> Direct transfer from your account, works with all banks, audit trail for both parties</p>
    </div>

    <!-- Credit Card Installment -->
    <div class="payment-card">
        <h2><i class="fas fa-credit-card"></i> Credit Card Installment (0% Interest)</h2>
        <p>Spread your payment over 3, 6, or 12 months with zero interest fees.</p>
        
        <div class="highlights">
            <strong><i class="fas fa-check"></i> Flexible Payment Plans</strong><br>
            3-month, 6-month, or 12-month plans available with no interest charges
        </div>

        <strong>Available Plans:</strong>
        <p style="font-weight: 600; margin: 15px 0;">All major credit cards accepted: Visa, Mastercard, Amex, etc.</p>
        <ul style="line-height: 2;">
            <li><strong>3 Months:</strong> Lowest monthly payment, shorter commitment</li>
            <li><strong>6 Months:</strong> Balanced payment option, popular choice</li>
            <li><strong>12 Months:</strong> Smallest monthly payment spread</li>
        </ul>

        <div class="highlights">
            <strong>Next Step:</strong> Our team will contact you at checkout to arrange the installment payment through your bank's partner merchants or our secure payment gateway.
        </div>

        <p><strong>Advantages:</strong> Flexible payments, no interest charges, faster order processing with 0% plans</p>
    </div>

    <!-- Payment Comparison Table -->
    <div style="margin: 40px 0;">
        <h3 style="color: var(--primary-color); text-align: center; margin-bottom: 20px;">Payment Methods Comparison</h3>
        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Processing</th>
                    <th>Fee (Buyer)</th>
                    <th>Best For</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Cash on Delivery</strong></td>
                    <td><span class="badge badge-flexible">Upon Delivery</span></td>
                    <td>None</td>
                    <td>Cash preference, inspection before pay</td>
                    <td>Metro Manila & Bulacan</td>
                </tr>
                <tr>
                    <td><strong>GCash</strong></td>
                    <td><span class="badge badge-instant">Instant</span></td>
                    <td>None</td>
                    <td>Fastest ordering, quick delivery</td>
                    <td>24/7 for GCash users</td>
                </tr>
                <tr>
                    <td><strong>Maya</strong></td>
                    <td><span class="badge badge-instant">Instant</span></td>
                    <td>None</td>
                    <td>Earn rewards, secure payment</td>
                    <td>24/7 for Maya users</td>
                </tr>
                <tr>
                    <td><strong>Bank Transfer</strong></td>
                    <td><span class="badge badge-fast">1-3 Hours</span></td>
                    <td>Varies by bank</td>
                    <td>Large amounts, bank reconciliation</td>
                    <td>Business hours (InstaPay) or 24/7 (PesoNet)</td>
                </tr>
                <tr>
                    <td><strong>Credit Installment</strong></td>
                    <td><span class="badge badge-fast">1-2 Hours</span></td>
                    <td>None (0%)</td>
                    <td>Large orders, budget management</td>
                    <td>All credit card days</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h3 style="color: var(--primary-color); text-align: center; margin-bottom: 20px;">Frequently Asked Questions</h3>
        
        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i> Is it safe to send money via GCash/Maya?
            </div>
            <div class="faq-answer">
                Yes, both GCash and Maya are officially regulated by the BSP (Bangko Sentral ng Pilipinas) and use bank-level encryption for all transactions.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i> What if I can't send payment proof immediately?
            </div>
            <div class="faq-answer">
                You can send the screenshot proof anytime within 24 hours. Once we receive it, your order will be confirmed and sent for delivery.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i> Can I change payment method after ordering?
            </div>
            <div class="faq-answer">
                Contact us immediately if you need to change your payment method. We'll help you adjust the order details.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i> Do you offer cash back?
            </div>
            <div class="faq-answer">
                For digital payments, we cannot process cash back. For COD orders, our drivers only accept exact payment.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <i class="fas fa-question-circle"></i> How long does order confirmation take?
            </div>
            <div class="faq-answer">
                <strong>GCash/Maya:</strong> Instant (within minutes)<br>
                <strong>Bank Transfer:</strong> 1-3 hours during business hours<br>
                <strong>Credit Installment:</strong> 1-2 hours after approval<br>
                <strong>COD:</strong> Confirmed upon order placement
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <h3><i class="fas fa-phone"></i> Still Have Questions?</h3>
        <p>Our customer service team is ready to help you with payment inquiries</p>
        
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <strong>Email</strong>
                <p>tinapa@example.com</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <strong>Phone</strong>
                <p>0917-123-4567</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-comment"></i>
                <strong>Facebook Messenger</strong>
                <p>PTL Best Tinapa</p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
