<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>You've Been Outbid - KlikBid</title>
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
        .alert-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-icon {
            font-size: 48px;
            margin-bottom: 10px;
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
        .price {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
        }
        .cta-button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🛍️ KlikBid</div>
            <p>Sri Lanka's Premier Online Auction Platform <span class="sri-lanka-flag">🇱🇰</span></p>
        </div>

        <!-- Alert Box -->
        <div class="alert-box">
            <div class="alert-icon">⚠️</div>
            <h2 style="margin: 0; color: #d97706;">You've Been Outbid!</h2>
            <p style="margin: 10px 0 0 0;">Someone placed a higher bid on your auction</p>
        </div>

        <!-- Greeting -->
        <p>Hello <strong>{{ $bidder->name }}</strong>,</p>

        <p>We wanted to let you know that someone has placed a higher bid on an auction you were bidding on. Don't worry - you still have time to place a new bid!</p>

        <!-- Auction Details -->
        <div class="auction-details">
            <h3 style="margin-top: 0; color: #374151;">📦 Auction Details</h3>

            <div class="detail-row">
                <span class="detail-label">Auction:</span>
                <span class="detail-value">{{ $auction->title }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Category:</span>
                <span class="detail-value">{{ $auction->category->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Your Previous Bid:</span>
                <span class="detail-value price">Rs. {{ number_format($yourBid->amount) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Current Highest Bid:</span>
                <span class="detail-value price">Rs. {{ number_format($auction->current_bid) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Auction Ends:</span>
                <span class="detail-value">{{ $auction->end_time->format('M d, Y \a\t h:i A') }}</span>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ route('auctions.show', $auction->id) }}" class="cta-button">
                🔥 Place a Higher Bid Now
            </a>
        </div>

        <p><strong>💡 Quick Tip:</strong> Auction bidding can be competitive! Consider setting a maximum amount you're willing to pay and bid strategically as the auction nears its end.</p>

        <!-- Footer -->
        <div class="footer">
            <p><strong>KlikBid - Sri Lanka's Trusted Auction Platform</strong></p>
            <p>🏆 Connecting buyers and sellers across beautiful Sri Lanka 🇱🇰</p>
            <p style="font-size: 12px;">
                This email was sent because you have an active bid on this auction.<br>
                You can manage your email preferences in your account settings.
            </p>
        </div>
    </div>
</body>
</html>
