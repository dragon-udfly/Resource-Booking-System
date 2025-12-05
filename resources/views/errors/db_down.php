<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error</title>
</head>
<body>
    <div style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9); /* Dark overlay */
        z-index: 99999; /* Always on top */
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 20px;
    ">
        <div style="
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 90%;
        ">
            <h2 style="font-size: 1.8em; margin-bottom: 15px; color: #dc3545;">Connection Lost</h2>
            
            <p style="
                font-size: 1.2em;
                margin-bottom: 25px;
                font-weight: bold;
            ">CRITICAL ERROR: No database connection found.</p>
            
            <p style="font-size: 1em; margin-bottom: 30px;">
                Please verify your configuration in the <strong>.env</strong> file and ensure the MySQL server is running.
            </p>

            <button onclick="location.reload()" style="
                padding: 12px 25px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                background-color: #dc3545; /* Red button */
                color: white;
                font-weight: bold;
                font-size: 1em;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: background-color 0.3s;
            " 
            onmouseover="this.style.backgroundColor='#c82333'" 
            onmouseout="this.style.backgroundColor='#dc3545'">
                Retry Connection
            </button>
        </div>
    </div>
</body>
</html>