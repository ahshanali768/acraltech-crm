<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
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
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .user-info {
            background: #f7fafc;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e2e8f0;
        }
        .user-info h3 {
            margin: 0 0 20px 0;
            color: #2d3748;
            font-size: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            align-items: center;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #2d3748;
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            flex: 1;
        }
        .action-buttons {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-approve {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }
        .btn-approve:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
        }
        .btn-reject {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }
        .btn-reject:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
        }
        .manage-link {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: #edf2f7;
            border-radius: 8px;
        }
        .manage-link a {
            color: #4299e1;
            text-decoration: none;
            font-weight: 600;
        }
        .manage-link a:hover {
            text-decoration: underline;
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
        .priority-badge {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="priority-badge">🔔 ADMIN ACTION REQUIRED</div>
            <h1>New User Registration</h1>
            <p>A new user has registered and requires approval</p>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            
            <p>A new user has successfully completed email verification and is now awaiting admin approval to access the system.</p>
            
            <div class="user-info">
                <h3>👤 User Information</h3>
                <div class="info-row">
                    <div class="info-label">Full Name:</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Username:</div>
                    <div class="info-value">{{ $user->username }}</div>
                </div>
                @if($user->phone)
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $user->phone }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Registered:</div>
                    <div class="info-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Current Status:</div>
                    <div class="info-value">
                        <span style="color: #ed8936; font-weight: 600;">⏳ Pending Approval</span>
                    </div>
                </div>
            </div>
            
            <p><strong>Quick Actions:</strong> You can approve or reject this user directly from this email:</p>
            
            <div class="action-buttons">
                <a href="{{ $approveUrl }}" class="btn btn-approve">
                    ✅ Approve User
                </a>
                <a href="{{ $rejectUrl }}" class="btn btn-reject">
                    ❌ Reject User
                </a>
            </div>
            
            <div class="manage-link">
                <p>
                    <strong>Advanced Management:</strong><br>
                    For role assignment and detailed management, visit the 
                    <a href="{{ route('admin.manage_users') }}">User Management Dashboard</a>
                </p>
            </div>
            
            <p style="margin-top: 30px; padding: 15px; background: #ebf8ff; border-radius: 8px; border-left: 4px solid #4299e1;">
                <strong>💡 Note:</strong> The user will receive an automated notification email once you approve or reject their account.
            </p>
        </div>
        
        <div class="footer">
            <p>
                <span class="company-name">{{ config('app.name') }} Admin System</span><br>
                Automated Admin Notification
            </p>
            <p style="margin-top: 15px; color: #a0aec0; font-size: 12px;">
                This email was sent to all administrators. Please take action as soon as possible.
            </p>
        </div>
    </div>
</body>
</html>
