<?php
/**
 * Forgot Password (Placeholder)
 * SDO San Pedro City DTR System
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SDO San Pedro City DTR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h1 {
            color: #0066cc;
            margin-bottom: 20px;
        }

        .info-box {
            background: #d1ecf1;
            color: #0c5460;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
        }

        .info-box h3 {
            margin-bottom: 10px;
        }

        .info-box p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }

        .back-link:hover {
            background: #0052a3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        
        <div class="info-box">
            <h3>Password Reset Assistance</h3>
            <p>For password reset requests, please contact:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>SDO San Pedro City ICT Office</strong></li>
                <li>Email: <strong>ict@sdosanpedrocity.edu.ph</strong></li>
                <li>Phone: <strong>[Contact Number]</strong></li>
            </ul>
            <p style="margin-top: 15px;">
                <strong>Note:</strong> This feature is currently under development. 
                Please contact your system administrator for password assistance.
            </p>
        </div>

        <a href="login.php" class="back-link">← Back to Login</a>
    </div>
</body>
</html>
