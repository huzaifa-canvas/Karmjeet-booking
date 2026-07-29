<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - Karmjeet Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-color: #0f172a;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Gradients */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 10s infinite ease-in-out alternate;
        }

        .shape-1 {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            width: 400px;
            height: 400px;
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        .shape-2 {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            width: 300px;
            height: 300px;
            border-radius: 50%;
            bottom: -50px;
            right: -50px;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        /* Glassmorphism Container */
        .container {
            position: relative;
            z-index: 1;
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 4rem;
            border-radius: 24px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
            background: linear-gradient(to right, #c084fc, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        p {
            font-size: 1.25rem;
            font-weight: 300;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* Pulsing dot */
        .badge::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #818cf8;
            border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 10px #818cf8;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(129, 140, 248, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(129, 140, 248, 0); }
            100% { box-shadow: 0 0 0 0 rgba(129, 140, 248, 0); }
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            text-decoration: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.5);
        }

        .btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="container">
        <div class="badge">Site Under Maintenance</div>
        <h1>Coming Soon</h1>
        <p>We are currently upgrading our platform to bring you a better experience. We'll be back online shortly!</p>
        <button class="btn" onclick="window.location.reload()">Refresh Page</button>
    </div>
</body>
</html>
