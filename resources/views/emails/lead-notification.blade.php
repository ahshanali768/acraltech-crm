<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($type) }} Lead Notification</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #10b981, #06b6d4);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-new { background: #dbeafe; color: #1e40af; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
        .lead-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 10px 5px;
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .footer a {
            color: #10b981;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ ucfirst($type) }} Lead Notification</h1>
            <span class="status-badge status-{{ $type }}">{{ ucfirst($type) }}</span>
        </div>
        
        <div class="content">
            @if($type === 'new')
                <p>You have received a new lead for the <strong>{{ $lead->campaign->name }}</strong> campaign.</p>
            @elseif($type === 'approved')
                <p>The lead for <strong>{{ $lead->full_name }}</strong> has been approved and is ready for contact.</p>
            @elseif($type === 'rejected')
                <p>The lead for <strong>{{ $lead->full_name }}</strong> has been rejected. Please review the details below.</p>
            @endif
            
            <div class="lead-details">
                <h3 style="margin-top: 0; color: #111827;">Lead Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value">{{ $lead->full_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $lead->email }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $lead->phone }}</span>
                </div>
                
                @if($lead->city && $lead->state)
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $lead->city }}, {{ $lead->state }}</span>
                </div>
                @endif
                
                @if($lead->age)
                <div class="detail-row">
                    <span class="detail-label">Age:</span>
                    <span class="detail-value">{{ $lead->age }} years</span>
                </div>
                @endif
                
                @if($lead->annual_income)
                <div class="detail-row">
                    <span class="detail-label">Annual Income:</span>
                    <span class="detail-value">${{ number_format($lead->annual_income) }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Campaign:</span>
                    <span class="detail-value">{{ $lead->campaign->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Lead Source:</span>
                    <span class="detail-value">{{ $lead->lead_source ?? 'Direct' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Created:</span>
                    <span class="detail-value">{{ $lead->created_at->format('M j, Y \a\t g:i A') }}</span>
                </div>
                
                @if($lead->notes)
                <div class="detail-row">
                    <span class="detail-label">Notes:</span>
                    <span class="detail-value">{{ $lead->notes }}</span>
                </div>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                @if($type === 'new')
                    <a href="{{ route('admin.view_leads') }}" class="btn">Review Lead</a>
                    <a href="{{ route('admin.view_leads') }}" class="btn">View Leads</a>
                @elseif($type === 'approved')
                    <a href="tel:{{ $lead->phone }}" class="btn">Call Now</a>
                    <a href="mailto:{{ $lead->email }}" class="btn">Send Email</a>
                @endif
            </div>
            
            @if($type === 'new')
                <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin: 20px 0;">
                    <strong style="color: #92400e;">Action Required:</strong>
                    <span style="color: #78350f;">Please review and approve/reject this lead within 24 hours to maintain quality standards.</span>
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>This notification was sent by <a href="{{ url('/') }}">Acraltech Solutions LLP</a></p>
            <p>© {{ date('Y') }} Acraltech Solutions LLP. All rights reserved.</p>
            <p>
                <a href="{{ url('/') }}">Dashboard</a> | 
                <a href="{{ url('/privacy') }}">Privacy Policy</a> | 
                <a href="mailto:info@acraltech.com">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>
