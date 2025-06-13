<?php
session_start();
include 'db.php';

$question_number = isset($_GET['q']) ? (int)$_GET['q'] : 1;
$total_questions = 10; // Adjust based on database content

// Fetch question from database
$stmt = $pdo->prepare("SELECT * FROM questions WHERE question_id = ?");
$stmt->execute([$question_number]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question && $question_number <= $total_questions) {
    // Placeholder if question not found
    $question = [
        'question_id' => $question_number,
        'question_text' => "Sample Question $question_number: What comes next in the sequence 2, 4, 6, 8?",
        'option_a' => '10',
        'option_b' => '12',
        'option_c' => '14',
        'option_d' => '16',
        'correct_option' => 'option_a'
    ];
}

// Store answer if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'] ?? '';
    $_SESSION['answers'][$question_number] = $answer;
    $next_question = $question_number + 1;
    if ($next_question > $total_questions) {
        echo "<script>window.location.href = 'results.php';</script>";
    } else {
        echo "<script>window.location.href = 'quiz.php?q=$next_question';</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test - Quiz</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .quiz-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 90%;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            font-size: 1.8em;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .options label {
            display: block;
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .options label:hover {
            background: #e0e0e0;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .nav-btn {
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .nav-btn:hover {
            background: #2980b9;
        }
        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .quiz-container {
                padding: 20px;
                margin: 10px;
            }
            h2 {
                font-size: 1.5em;
            }
            .options label {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2>Question <?php echo $question_number; ?> of <?php echo $total_questions; ?></h2>
        <p><?php echo htmlspecialchars($question['question_text']); ?></p>
        <form method="POST">
            <div class="options">
                <label><input type="radio" name="answer" value="option_a" required> <?php echo htmlspecialchars($question['option_a']); ?></label>
                <label><input type="radio" name="answer" value="option_b"> <?php echo htmlspecialchars($question['option_b']); ?></label>
                <label><input type="radio" name="answer" value="option_c"> <?php echo htmlspecialchars($question['option_c']); ?></label>
                <label><input type="radio" name="answer" value="option_d"> <?php echo htmlspecialchars($question['option_d']); ?></label>
            </div>
            <div class="navigation">
                <button type="button" class="nav-btn" onclick="goBack()" <?php if ($question_number == 1) echo 'disabled'; ?>>Previous</button>
                <button type="submit" class="nav-btn"><?php echo $question_number == $total_questions ? 'Finish' : 'Next'; ?></button>
            </div>
        </form>
    </div>
    <script>
        function goBack() {
            const prevQuestion = <?php echo $question_number - 1; ?>;
            if (prevQuestion >= 1) {
                window.location.href = 'quiz.php?q=' + prevQuestion;
            }
        }
    </script>
</body>
</html>
