<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developers - District Secretariat Vavuniya</title>
    <style>
        body {
            background-color: #0056b3; /* Dark blue background */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
            min-height: 100vh; /* Full viewport height */
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .developers-box {
            background-color: #fff;
            color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 80%; /* Adjust width as needed */
            max-width: 500px; /* Max width for larger screens */
        }
        .developers-box h2 {
            margin-bottom: 25px;
            color: #0056b3;
            font-size: 2em;
        }
        .developers-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .developers-box li {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="developers-box">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" style="background-color: #6c757d;" class="btn">Home</a>
        </div>
        <h2>Our Developers</h2>
        <ul>
            <li>John Doe</li>
            <li>Jane Smith</li>
            <li>Robert Johnson</li>
            <li>Emily Davis</li>
            <li>Michael Brown</li>
            <li>Jessica Wilson</li>
        </ul>
    </div>
</body>
</html>