<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - CommuniTap</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f1f1f1;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background-color: #FFE8D6;
            padding: 20px 20px;
            text-align: center;
        }
        .logo {
            max-width: 90px;
            width: 90px;
            height: auto;
            margin: 0;
            -ms-interpolation-mode: nearest-neighbor;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a4a4a;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 16px 40px;
            background-color: #F3A261;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 6px 16px rgba(243, 162, 97, 0.3);
            transition: all 0.3s ease;
        }
        .verify-button:link,
        .verify-button:visited,
        .verify-button:hover,
        .verify-button:active {
            color: #ffffff !important;
            text-decoration: none !important;
        }
        .verify-button:hover {
            background-color: #E8944F;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(243, 162, 97, 0.4);
        }
        .footer-text {
            font-size: 14px;
            color: #6b7280;
            margin-top: 30px;
            line-height: 1.6;
        }
        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }
        .troubleshoot {
            font-size: 14px;
            color: #6b7280;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .url-link {
            word-break: break-all;
            color: #F3A261;
            text-decoration: none;
            font-size: 13px;
        }
        .url-link:hover {
            text-decoration: underline;
        }
        .signature {
            margin-top: 30px;
            color: #4a4a4a;
        }
        .signature-name {
            font-weight: 600;
            color: #2B9D8D;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Gradient Background -->
        <div class="email-header">
            @if(isset($logoUrl) && !empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="CommuniTap Logo" class="logo" style="max-width: 90px; width: 90px; height: auto; display: block; margin: 0 auto; border: 0; -ms-interpolation-mode: nearest-neighbor; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
            @else
                <!-- Fallback: Text Logo -->
                <div style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 20px 0;">
                    <span style="color: #1a1a1a;">COMMUNI</span><span style="color: #F3A261;">TAP</span>
                </div>
            @endif
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <div class="greeting">Hello!</div>
            
            <div class="message">
                Thank you for registering with <strong style="color: #F3A261;">CommuniTAP</strong>. We appreciate your interest in joining our community.
            </div>

            <div class="message">
                To complete your registration and activate your account, please verify your email address by clicking the button below:
            </div>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button" style="color: #ffffff !important; text-decoration: none !important;">Verify Email Address</a>
            </div>

            <div class="footer-text">
                If you did not create an account with CommuniTAP, please disregard this email. No further action is required, and you may safely ignore this message.
            </div>

            <div class="divider"></div>

            <div class="troubleshoot">
                <p style="margin-bottom: 15px; font-weight: 600; color: #4a4a4a;">Unable to access the button above?</p>
                <p style="margin-bottom: 10px; color: #6b7280;">You may copy and paste the following URL into your web browser's address bar:</p>
                <a href="{{ $verificationUrl }}" class="url-link">{{ $verificationUrl }}</a>
            </div>

            <div class="signature">
                <p style="margin: 0;">Sincerely,</p>
                <p style="margin: 5px 0 0 0;" class="signature-name">The CommuniTAP Support Team</p>
            </div>
        </div>
    </div>
</body>
</html>

