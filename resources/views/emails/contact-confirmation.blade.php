<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting SupperAge</title>
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
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .content-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1DA1F2;
            margin: 20px 0;
        }
        .info-label {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }
        .info-value {
            color: #666;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 15px;
            font-weight: 600;
        }
        .social-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            background: #1DA1F2;
            color: white;
            border-radius: 50%;
            margin: 0 5px;
            text-decoration: none;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            color: #1DA1F2;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✅ Message Received!</h1>
            <p>Thank you for reaching out to us</p>
        </div>
        
        <div class="email-body">
            <p class="greeting">Hello {{ $data['name'] }},</p>
            
            <p>Thank you for contacting <strong>SupperAge</strong>! We have successfully received your message and our team will review it shortly.</p>
            
            <div class="content-box">
                <p><span class="info-label">📋 Your Message Summary:</span></p>
                
                <div style="margin-top: 15px;">
                    <span class="info-label">Subject:</span>
                    <div class="info-value">{{ $data['subject'] }}</div>
                </div>
                
                <div>
                    <span class="info-label">Message:</span>
                    <div class="info-value">{{ $data['message'] }}</div>
                </div>
            </div>
            
            <p>We typically respond to all inquiries within <span class="highlight">24-48 hours</span>. Our support team will get back to you at <strong>{{ $data['email'] }}</strong> as soon as possible.</p>
            
            <p>In the meantime, feel free to explore more about SupperAge:</p>
            
            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Visit SupperAge</a>
            </p>
            
            <div class="social-links">
                <p style="margin-bottom: 10px; color: #666;">Connect with us:</p>
                <a href="#" title="Facebook">f</a>
                <a href="#" title="Twitter">t</a>
                <a href="#" title="Instagram">i</a>
                <a href="#" title="LinkedIn">in</a>
            </div>
        </div>
        
        <div class="email-footer">
            <p><strong>SupperAge</strong> - Connect. Share. Earn.</p>
            <p>Email: <a href="mailto:info@supperage.com">info@supperage.com</a></p>
            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                This is an automated confirmation email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>