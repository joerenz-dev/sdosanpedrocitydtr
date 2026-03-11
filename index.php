<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDO San Pedro City DTR System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        img {
            display: block;
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }

        /* Popup overlay */
        #wfh-popup {
            display: flex;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        #wfh-popup .popup-box {
            background: #fff;
            border-radius: 16px;
            padding: 40px 36px 32px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
            font-family: 'Century Gothic', Arial, sans-serif;
        }

        #wfh-popup .popup-box img.popup-logo {
            height: 64px;
            margin-bottom: 16px;
        }

        #wfh-popup .popup-box h2 {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        #wfh-popup .popup-box p {
            font-size: 0.92rem;
            color: #555;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        #wfh-popup .popup-btn {
            display: inline-block;
            background: linear-gradient(135deg, #1a6fc4, #0a3d7a);
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            padding: 13px 32px;
            border-radius: 50px;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }

        #wfh-popup .popup-btn:hover {
            opacity: 0.88;
        }

        #wfh-popup .close-btn {
            display: block;
            margin-top: 16px;
            background: none;
            border: none;
            color: #888;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: underline;
        }

        #wfh-popup .close-btn:hover {
            color: #444;
        }
    </style>
</head>
<body>
    <img src="img/feature-coming-soon.png" alt="SDO San Pedro City DTR System">

    <!-- WFH Redirect Popup -->
    <div id="wfh-popup">
        <div class="popup-box">
            <img class="popup-logo" src="frontpage-image/SDO Sanpedro Logo.png" alt="SDO San Pedro City Logo">
            <h2>SDO San Pedro City DTR System</h2>
            <p>Click the button below to be redirected to the <strong>Work From Home (WFH) portal</strong> of SDO San Pedro City.</p>
            <a class="popup-btn" href="https://wfh-sdospc.com/login.php" target="_blank" rel="noopener noreferrer">
                wfh-sdospc
            </a>
            <button class="close-btn" onclick="document.getElementById('wfh-popup').style.display='none'">
                Dismiss
            </button>
        </div>
    </div>
</body>
</html>
S