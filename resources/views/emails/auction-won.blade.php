<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Congratulations! You Won - KlikBid</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .celebration-box {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .celebration-box::before {
            content: "🎉🎊✨🏆✨🎊🎉";
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            font-size: 20px;
            animation: confetti 3s ease-in-out infinite;
        }
        @keyframes confetti {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(180deg); }
        }
        .celebration-icon {
            font-size: 64px;
            margin-bottom: 15px;
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .auction-details {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
        }
        .detail-value {
            color: #374151;
        }
        .winning-price {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            background-color: #d1fae5;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps {
            background-color: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-radius: 6px;
        }
        .step-number {
            background-color: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .sri-lanka-flag {
            color: #ff6b35;
        }
        .seller-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🛍️ KlikBid</div>
            <p>Sri Lanka's Premier Online Auction Platform <span class="sri-lanka-flag">🇱🇰</span></p>
        </div>

        <!-- Celebration Box -->
        <div class="celebration-box">
            <div class="celebration-icon">🏆</div>
            <h1 style="margin: 0; font-size: 32px;">CONGRATULATIONS!</h1>
            <h2 style="margin: 10px 0 0 0; font-size: 20px;">You Won the Auction!</h2>
        </div>

        <!-- Greeting -->
        <p>Dear <strong>{{ $winner->name }}</strong>,</p>

        <p>🎉 <strong>Fantastic news!</strong> You have successfully won the auction! Your winning bid has secured this amazing item. We're excited to help you complete your purchase.</p>

        <!-- Winning Price -->
        <div class="winning-price">
            🎯 Your Winning Bid: Rs. {{ number_format($auction->current_bid) }}
        </div>

        <!-- Auction Details -->
        <div class="auction-details">
            <h3 style="margin-top: 0; color: #374151;">📦 Item Details</h3>

            <div class="detail-row">
                <span class="detail-label">Item:</span>
                <span class="detail-value">{{ $auction->title }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Category:</span>
                <span class="detail-value">{{ $auction->category->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Auction Ended:</span>
                <span class="detail-value">{{ $auction->end_time->format('M d, Y \a\t h:i A') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Bids Received:</span>
                <span class="detail-value">{{ $auction->bids->count() }} bids</span>
            </div>
        </div>

        <!-- Seller Information -->
        <div class="seller-info">
            <h4 style="margin-top: 0; color: #92400e;">📞 Seller Information</h4>
            <p><strong>Seller:</strong> {{ $auction->user->name }}</p>
            <p><strong>Email:</strong> {{ $auction->user->email }}</p>
            <p style="margin-bottom: 0;"><strong>Note:</strong> Our team will facilitate the connection between you and the seller for payment and delivery arrangements.</p>
        </div>

        <!-- Next Steps -->
        <div class="next-steps">
            <h3 style="margin-top: 0; color: #1d4ed8;">🚀 What Happens Next?</h3>

            <div class="step">
                <div class="step-number">1</div>
                <div>
                    <strong>Payment Coordination</strong><br>
                    <small>Our team will contact you within 24 hours with payment instructions and seller contact details.</small>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div>
                    <strong>Seller Contact</strong><br>
                    <small>You'll be connected with the seller to arrange payment method and delivery/pickup options.</small>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div>
                    <strong>Complete Transaction</strong><br>
                    <small>Coordinate with the seller for payment and receive your won item!</small>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('user.dashboard') }}" class="cta-button">
                📋 View in My Dashboard
            </a>
        </div>

        <!-- Important Notes -->
        <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #dc2626;">⚠️ Important Reminders:</h4>
            <ul style="margin-bottom: 0;">
                <li><strong>Payment:</strong> Complete payment as arranged with the seller</li>
                <li><strong>Timeline:</strong> Most sellers expect payment within 3-5 business days</li>
                <li><strong>Communication:</strong> Be responsive to seller messages</li>
                <li><strong>Support:</strong> Contact us if you experience any issues</li>
            </ul>
        </div>

        <p>🙏 Thank you for using KlikBid! We hope you enjoy your new purchase and continue bidding on our platform.</p>

        <!-- Footer -->
        <div class="footer">
            <p><strong>KlikBid - Sri Lanka's Trusted Auction Platform</strong></p>
            <p>🏆 Connecting buyers and sellers across beautiful Sri Lanka 🇱🇰</p>
            <p style="font-size: 12px;">
                Questions? Contact our support team at support@klikbid.lk<br>
                🕒 Business Hours: Monday-Friday 9AM-6PM (Sri Lanka Time)
            </p>
        </div>
    </div>
</body>
</html>
