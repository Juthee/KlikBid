<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auction Ending Soon - KlikBid</title>
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
            background-color: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .countdown {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .countdown-time {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
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
            color: #059669;
        }
        .cta-button {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .leading {
            background-color: #d1fae5;
            color: #065f46;
        }
        .losing {
            background-color: #fee2e2;
            color: #991b1b;
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
            <div class="alert-icon">⏰</div>
            <h2 style="margin: 0; color: #dc2626;">Auction Ending Soon!</h2>
            <p style="margin: 10px 0 0 0;">Don't miss your chance to win this item</p>
        </div>

        <!-- Countdown -->
        <div class="countdown">
            <div>⏳ <strong>TIME REMAINING</strong></div>
            <div class="countdown-time">{{ $timeRemaining }}</div>
            <div>Until auction closes</div>
        </div>

        <!-- Greeting -->
        <p>Hello <strong>{{ $bidder->name }}</strong>,</p>

        <p>This is a friendly reminder that an auction you're bidding on will be ending soon!
        @if($isWinning)
            <span class="status-badge leading">🏆 You're Currently Winning!</span>
        @else
            <span class="status-badge losing">📈 You're Currently Losing</span>
        @endif
        </p>

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
                <span class="detail-label">Your Current Bid:</span>
                <span class="detail-value price">Rs. {{ number_format($yourBid->amount) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Current Highest Bid:</span>
                <span class="detail-value price">Rs. {{ number_format($auction->current_bid) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Auction Ends:</span>
                <span class="detail-value"><strong>{{ $auction->end_time->format('M d, Y \a\t h:i A') }}</strong></span>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center;">
            @if($isWinning)
                <a href="{{ route('auctions.show', $auction->id) }}" class="cta-button">
                    🛡️ Defend Your Lead!
                </a>
                <p><strong>🎉 Great job!</strong> You're currently the highest bidder, but stay alert - other bidders might make last-minute bids!</p>
            @else
                <a href="{{ route('auctions.show', $auction->id) }}" class="cta-button">
                    🚀 Place Your Final Bid!
                </a>
                <p><strong>💡 Last chance!</strong> This auction is ending soon. Place your bid now to secure this item!</p>
            @endif
        </div>

        <!-- Tips -->
        <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #1d4ed8;">🧠 Bidding Tips:</h4>
            <ul style="margin-bottom: 0;">
                <li>Set your maximum bid and stick to it</li>
                <li>Last-minute bidding is common - be ready!</li>
                <li>Check the auction page for any updates</li>
                <li>Remember: winning bidders must complete payment</li>
            </ul>
        </div>

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
