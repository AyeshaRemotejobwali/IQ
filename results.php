<?php
session_start();
include 'db.php';

// Calculate score
$answers = $_SESSION['answers'] ?? [];
$score = 0;
$total_questions = 10;

foreach ($answers as $question_id => $user_answer) {
    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE question_id = ?");
    $stmt->execute([$question_id]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($question && $user_answer === $question['correct_option']) {
        $score++;
    }
}

// Calculate IQ score (simplified: 100 is average, each correct answer adds ~5 points)
$iq_score = 100 + ($score - 5) * 5;

// Feedback based on score
$feedback = match (true) {
    $iq_score >= 130 => "Exceptional! Your cognitive abilities are in the top percentile, showing outstanding logical reasoning and problem-solving skills.",
    $iq_score >= 115 => "Above average! You have strong cognitive skills, particularly in pattern recognition and logical thinking.",
    $iq_score >= 85 => "Average performance. You have a solid foundation in cognitive abilities, with room to improve in specific areas.",
    default => "Below average. Consider practicing logical reasoning and pattern recognition to enhance your skills."
};

// Save result to database
$stmt = $pdo->prepare("INSERT INTO results (score, iq_score, test_date) VALUES (?, ?, NOW())");
$stmt->execute([$score, $iq_score]);

// Clear session answers
$_SESSION['answers'] = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test - Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .results-container {
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
        .score {
            font-size: 3em;
            color: #3498db;
            margin: 20px 0;
        }
        p {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            background: #3498db;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .results-container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 2em;
            }
            .score {
                font-size: 2.5em;
            }
            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h1>Your IQ Test Results</h1>
        <div class="score"><?php echo $iq_score; ?></div>
        <p><?php echo htmlspecialchars($feedback); ?></p>
        <button class="btn" onclick="retakeTest()">Retake Test</button>
        <button class="btn" onclick="shareResults()">Share Results</button>
    </div>
    <script>
        function retakeTest() {
            window.location.href = 'quiz.php?q=1';
        }
        function shareResults() {
            alert('Share your IQ score of <?php echo $iq_score; ?> with friends!');
            // Add actual sharing functionality if needed
        }
    </script>
</body>
</html>
