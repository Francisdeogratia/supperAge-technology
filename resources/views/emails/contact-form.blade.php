<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            display: block;
        }
        .value {
            color: #666;
        }
        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1DA1F2;
            margin-top: 10px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #1DA1F2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 New Contact Form Submission</h1>
        </div>
        
        <div class="email-body">
            <p>You have received a new message from the SupperAge contact form:</p>
            
            <div class="info-row">
                <span class="label">👤 Name:</span>
                <span class="value">{{ $data['name'] }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">📧 Email:</span>
                <span class="value">
                    <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
                </span>
            </div>
            
            <div class="info-row">
                <span class="label">🏷️ Subject:</span>
                <span class="value">{{ $data['subject'] }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">💬 Message:</span>
                <div class="message-box">
                    {{ $data['message'] }}
                </div>
            </div>
            
            <p style="margin-top: 25px;">
                <a href="mailto:{{ $data['email'] }}" class="btn">Reply to {{ $data['name'] }}</a>
            </p>
        </div>
        
        <div class="email-footer">
            <p>This email was sent from the SupperAge contact form.</p>
            <p>Received on {{ date('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>