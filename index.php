<?php
session_start();
$_SESSION['answers'] = []; // Initialize session for storing answers
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .start-btn {
            background: #3498db;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .start-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 2em;
            }
            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the IQ Test</h1>
        <p>Discover your cognitive abilities with our carefully designed Intelligence Quotient (IQ) test. This test assesses your logical reasoning, pattern recognition, and problem-solving skills. Ready to challenge your mind?</p>
        <button class="start-btn" onclick="startTest()">Start Test</button>
    </div>
    <script>
        function startTest() {
            window.location.href = 'quiz.php';
        }
    </script>
</body>
</html>
