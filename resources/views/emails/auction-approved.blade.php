<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auction Approved - KlikBid</title>
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
        .approval-box {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
        }
        .approval-icon {
            font-size: 48px;
            margin-bottom: 10px;
            animation: checkmark 1.5s ease-in-out;
        }
        @keyframes checkmark {
            0% { transform: scale(0) rotate(0deg); }
            50% { transform: scale(1.2) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
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
        .auction-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .tips-section {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
        }
        .tip-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .tip-icon {
            margin-right: 10px;
            margin-top: 2px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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
        .status-badge {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
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

        <!-- Approval Box -->
        <div class="approval-box">
            <div class="approval-icon">✅</div>
            <h2 style="margin: 0; font-size: 24px;">Auction Approved!</h2>
            <p style="margin: 10px 0 0 0;">Your item is now live and ready for bidding</p>
        </div>

        <!-- Greeting -->
        <p>Dear <strong>{{ $seller->name }}</strong>,</p>

        <p>🎉 <strong>Great news!</strong> Your auction has been reviewed and approved by our team. Your item is now live on KlikBid and ready to receive bids from buyers across Sri Lanka!</p>

        <!-- Auction Details -->
        <div class="auction-details">
            <h3 style="margin-top: 0; color: #374151;">📦 Your Auction Details</h3>

            <div class="detail-row">
                <span class="detail-label">Item:</span>
                <span class="detail-value">{{ $auction->title }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Category:</span>
                <span class="detail-value">{{ $auction->category->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Starting Bid:</span>
                <span class="detail-value">Rs. {{ number_format($auction->starting_bid) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Auction Duration:</span>
                <span class="detail-value">{{ $auction->duration }} days</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Ends On:</span>
                <span class="detail-value">{{ $auction->end_time ? $auction->end_time->format('M d, Y \a\t h:i A') : 'Not set' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value"><span class="status-badge">Live & Active</span></span>
            </div>
        </div>

        <!-- Auction Stats -->
        <div class="auction-stats">
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Current Bids</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $auction->duration }}</div>
                <div class="stat-label">Days Remaining</div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('auctions.show', $auction->id) }}" class="cta-button">
                👀 View Your Live Auction
            </a>
        </div>

        <!-- Tips for Sellers -->
        <div class="tips-section">
            <h4 style="margin-top: 0; color: #92400e;">💡 Tips to Maximize Your Auction Success:</h4>

            <div class="tip-item">
                <span class="tip-icon">📈</span>
                <div><strong>Monitor Your Auction:</strong> Check regularly for new bids and interested buyers</div>
            </div>

            <div class="tip-item">
                <span class="tip-icon">📱</span>
                <div><strong>Share Your Listing:</strong> Tell friends and family about your auction to increase visibility</div>
            </div>

            <div class="tip-item">
                <span class="tip-icon">💬</span>
                <div><strong>Answer Questions:</strong> Respond promptly to any buyer inquiries about your item</div>
            </div>

            <div class="tip-item">
                <span class="tip-icon">📦</span>
                <div><strong>Prepare for Sale:</strong> Get ready to coordinate payment and delivery with the winning bidder</div>
            </div>
        </div>

        <!-- What Happens Next -->
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #1d4ed8;">🚀 What Happens Next?</h4>
            <ol style="margin-bottom: 0; padding-left: 20px;">
                <li><strong>Bidding Period:</strong> Buyers can now place bids on your item</li>
                <li><strong>Notifications:</strong> You'll receive email updates about new bids</li>
                <li><strong>Auction End:</strong> When the auction closes, we'll notify the winner</li>
                <li><strong>Payment & Delivery:</strong> You'll coordinate directly with the winning bidder</li>
            </ol>
        </div>

        <!-- Important Notes -->
        <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #dc2626;">⚠️ Seller Responsibilities:</h4>
            <ul style="margin-bottom: 0;">
                <li>Ensure your item description is accurate and complete</li>
                <li>Be responsive to winning bidder communications</li>
                <li>Arrange secure payment and delivery methods</li>
                <li>Contact KlikBid support if any issues arise</li>
            </ul>
        </div>

        <p>🙏 Thank you for choosing KlikBid to sell your item. We're excited to help you connect with buyers across Sri Lanka!</p>

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
