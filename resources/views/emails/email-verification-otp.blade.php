<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .otp-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: white;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .otp-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 10px;
        }
        .expiry-info {
            background: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .expiry-info p {
            margin: 0;
            color: #2d3748;
        }
        .security-note {
            background: #fffaf0;
            border: 1px solid #fbd38d;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
        }
        .security-note p {
            margin: 0;
            color: #744210;
            font-size: 14px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $userName }},</p>
            
            <p>Thank you for registering with <strong>{{ config('app.name') }}</strong>! To complete your registration and verify your email address, please use the following verification code:</p>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="expiry-info">
                <p><strong>⏰ Important:</strong> This verification code will expire in 10 minutes. Please verify your email address as soon as possible.</p>
            </div>
            
            <p>Once your email is verified, your account will be reviewed by our administrators for approval. You'll receive another notification once your account is approved and ready to use.</p>
            
            <div class="security-note">
                <p><strong>🔐 Security Note:</strong> If you didn't create an account with {{ config('app.name') }}, please ignore this email. Your email address will not be added to our system.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>
                Best regards,<br>
                <span class="company-name">{{ config('app.name') }} Team</span>
            </p>
            <p style="margin-top: 15px; color: #a0aec0; font-size: 12px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
