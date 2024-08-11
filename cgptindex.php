<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

require_once 'database/connect.php';

// ดึงข้อมูลบันทึกของผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notes WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Note-taking App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .note {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 5px solid #007BFF;
            border-radius: 5px;
        }

        .note h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .note p {
            margin: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .note small {
            display: block;
            margin-top: 10px;
            color: #555;
        }

        .add-note-link {
            display: block;
            margin-bottom: 20px;
            text-align: right;
        }

        .add-note-link a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .add-note-link a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h1>

        <div class="add-note-link">
            <a href="create_note.php">Add New Note</a>
        </div>

        <h2>Your Notes:</h2>
        <?php if ($notes): ?>
            <?php foreach ($notes as $note): ?>
                <div class="note">
                    <h2><?php echo htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($note['content'], ENT_QUOTES, 'UTF-8')); ?></p>
                    <small><?php echo htmlspecialchars($note['created_at'], ENT_QUOTES, 'UTF-8'); ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no notes yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>