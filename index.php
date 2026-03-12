<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDO San Pedro City Click Time</title>
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

        /* only target the hero/background image so small logos aren't affected */
        .hero-image {
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
            /* slightly darker overlay to make the modal and logos pop */
            background: rgba(0, 0, 0, 0.68);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        #wfh-popup .popup-box {
            /* slightly translucent white with subtle blur */
            background: rgba(255,255,255,0.92);
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            border-radius: 16px;
            padding: 40px 36px 32px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,0.06);
            font-family: 'Century Gothic', Arial, sans-serif;
        }

        /* logo row inside popup: two logos side-by-side */
        #wfh-popup .logo-row {
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        #wfh-popup .logo-row img {
            max-height: 72px;
            width: auto;
            object-fit: contain;
            display: block;
            transition: transform 180ms ease, filter 180ms ease; 
            filter: drop-shadow(0 6px 14px rgba(0,0,0,0.15));
        }

        /* slightly emphasize the sdoclick logo */
        #wfh-popup .logo-row img.sdoclick {
            transform: scale(1.06);
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.22));
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

        /* modal has no close button by design (persistent modal) */
    </style>
</head>
<body>
    <img class="hero-image" src="img/indexbg.png" alt="SDO San Pedro City DTR System">

    <!-- WFH Redirect Popup -->
    <div id="wfh-popup">
        <div class="popup-box">
            <div class="logo-row">
                <img src="frontpage-image/SDO Sanpedro Logo.png" alt="SDO San Pedro City Logo">
                <img src="img/sdoClick.png" alt="sdoclick logo" class="sdoclick">
            </div>
            <h2>SDO San Pedro City Click Time </h2>
            <p>Click the button below to be redirected to the <strong>Work From Home (WFH) Click Time portal</strong> of SDO San Pedro City.</p>
            <a class="popup-btn" href="https://wfh-sdospc.com/login.php" target="_blank" rel="noopener noreferrer">
                Click Time
            </a>
            <!-- Dismiss button removed so modal stays persistent -->
        </div>
    </div>
</body>
</html>
S