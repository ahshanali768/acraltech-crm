<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .header {
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header.approved {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }
        .header.rejected {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .status-box {
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .status-box.approved {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            border: 1px solid #48bb78;
        }
        .status-box.rejected {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            border: 1px solid #f56565;
        }
        .status-box h2 {
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .status-box.approved h2 {
            color: #1a202c;
        }
        .status-box.rejected h2 {
            color: #1a202c;
        }
        .status-box p {
            margin: 0;
            font-size: 16px;
            color: #2d3748;
        }
        .login-button {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }
        .btn:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5aa0 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
        }
        .details-box {
            background: #f7fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #4299e1;
        }
        .details-box h4 {
            margin: 0 0 10px 0;
            color: #2d3748;
        }
        .details-box p {
            margin: 0;
            color: #4a5568;
        }
        .notes {
            background: #fffaf0;
            border: 1px solid #fbd38d;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
        }
        .notes h4 {
            margin: 0 0 10px 0;
            color: #744210;
        }
        .notes p {
            margin: 0;
            color: #744210;
        }
        .footer {
            background: #f7fafc;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }
        .company-name {
            font-weight: 600;
            color: #4299e1;
        }
        .contact-info {
            background: #edf2f7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-info p {
            margin: 5px 0;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $status }}">
            <div class="icon">
                @if($status === 'approved')
                    🎉
                @else
                    😔
                @endif
            </div>
            <h1>
                @if($status === 'approved')
                    Account Approved!
                @else
                    Account Status Update
                @endif
            </h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>
            
            @if($status === 'approved')
                <div class="status-box approved">
                    <h2>🎊 Welcome to {{ config('app.name') }}!</h2>
                    <p>Your account has been successfully approved and activated. You can now log in and start using all the features of our platform.</p>
                </div>
                
                <p>We're excited to have you as part of our community! Your account is now fully active and you have access to:</p>
                
                <ul style="color: #4a5568; line-height: 2;">
                    <li>✅ Full platform access</li>
                    <li>✅ Lead management tools</li>
                    <li>✅ Analytics and reporting</li>
                    <li>✅ Customer support</li>
                </ul>
                
                <div class="login-button">
                    <a href="{{ $loginUrl }}" class="btn">
                        🚀 Access Your Account
                    </a>
                </div>
                
            @else
                <div class="status-box rejected">
                    <h2>Account Not Approved</h2>
                    <p>We regret to inform you that your account registration has not been approved at this time.</p>
                </div>
                
                <p>We appreciate your interest in {{ config('app.name') }}. Unfortunately, your account application did not meet our current requirements.</p>
                
                @if($notes)
                <div class="notes">
                    <h4>📝 Additional Information:</h4>
                    <p>{{ $notes }}</p>
                </div>
                @endif
                
                <div class="contact-info">
                    <p><strong>Need Help?</strong></p>
                    <p>If you believe this decision was made in error or if you have questions about the approval process, please contact our support team.</p>
                </div>
            @endif
            
            @if($approvedBy)
            <div class="details-box">
                <h4>📋 Approval Details</h4>
                <p><strong>Reviewed by:</strong> {{ $approvedBy->name }}</p>
                <p><strong>Date:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
                @if($status === 'approved')
                <p><strong>Role Assigned:</strong> {{ ucfirst($user->role) }}</p>
                @endif
            </div>
            @endif
            
            @if($status === 'approved')
                <p style="margin-top: 30px; padding: 15px; background: #ebf8ff; border-radius: 8px; border-left: 4px solid #4299e1;">
                    <strong>🔐 Security Reminder:</strong> Keep your login credentials secure and never share your password with anyone. If you need to reset your password, use the "Forgot Password" link on the login page.
                </p>
            @endif
        </div>
        
        <div class="footer">
            <p>
                Best regards,<br>
                <span class="company-name">{{ config('app.name') }} Team</span>
            </p>
            <p style="margin-top: 15px; color: #a0aec0; font-size: 12px;">
                This is an automated message. For support inquiries, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>
