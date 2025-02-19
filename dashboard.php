<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
        $stmt->execute([$user_id, $task]);
    } elseif (isset($_POST['edit_task']) && isset($_POST['task_id'])) {
        $task_id = $_POST['task_id'];
        $edited_task = $_POST['edit_task'];
        $stmt = $pdo->prepare("UPDATE tasks SET task = ? WHERE id = ?");
        $stmt->execute([$edited_task, $task_id]);
    } elseif (isset($_POST['delete_task'])) {
        $task_id = $_POST['delete_task'];
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);
    }
}

$tasks = $pdo->query("SELECT * FROM tasks WHERE user_id = $user_id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        header {
            background-color: #1a73e8;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        header img {
            height: 40px;
            margin-right: 10px;
        }

        header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Form Styles */
        .task-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .task-form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .task-form button {
            background-color: #1a73e8;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .task-form button:hover {
            background-color: #1558b1;
        }

        /* Task List */
        .task-list {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        .task-item {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .task-item .task-text {
            flex: 1;
            font-size: 16px;
        }

        .task-item button {
            background-color: #ff4d4f;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .task-item button:hover {
            background-color: #e24039;
        }

        /* Logout Link */
        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            color: #1a73e8;
            text-decoration: none;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        /* Voice Button */
        .voice-btn {
            background-color: #1a73e8;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            margin-left: auto;
        }

        .voice-btn:hover {
            background-color: #1558b1;
        }
    </style>
</head>
<body>
    <header>
        <img src="images/logo.png" alt="Logo">
        <h1>Productivity Assistant</h1>
    </header>

    <div class="container">
        <form class="task-form" method="POST">
            <input type="text" id="task-input" name="task" placeholder="Enter your task" required>
            <button type="submit">Add Task</button>
        </form>

        <h3>Your Tasks:</h3>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="task-item">
                    <span class="task-text"><?= htmlspecialchars($task['task']); ?></span>
                    <div>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                            <input type="text" class="edit-input" name="edit_task" placeholder="Edit task" required>
                            <button type="submit">Edit</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_task" value="<?= $task['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>

        <!-- Voice Input Button -->
        <button class="voice-btn" onclick="startDictation()">🎤 Voice Input</button>
    </div>

    <script>
        // Voice Input Script
        function startDictation() {
            if (window.hasOwnProperty('webkitSpeechRecognition')) {
                var recognition = new webkitSpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = "en-US";
                recognition.start();

                recognition.onresult = function(e) {
                    document.getElementById("task-input").value = e.results[0][0].transcript;
                    recognition.stop();
                };

                recognition.onerror = function(e) {
                    recognition.stop();
                };
            } else {
                alert("Speech Recognition API not supported.");
            }
        }
    </script>
</body>
</html>
