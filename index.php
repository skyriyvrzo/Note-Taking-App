<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

require_once 'database/connect.php';

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
    <link rel="icon" type="image/x-icon" href="./assets/images/pack.png">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #e8e8e8;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-container .header a {
            margin-left: 310px;
            font-size: 14px;
            text-decoration: none;
            color: #474747;
            padding: 6px 10px;
            border: 1px solid;
            border-radius: 5px;
            border-color: #bdbdbd;
        }

        .dashboard-container .header a:hover {
            border-color: black;
            color: black;
        }

        .dashboard-container h3 {
            margin-top: 20px;
        }

        .add-note-link {
            display: block;
            margin-top: 20px;
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

        .note {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 5px solid #007BFF;
            border-radius: 5px;

        }

        .note h2 {
            margin-top: 0;
            font-size: 1.3em;
            inline-size: 710px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .note small {
            font-size: 12px;
            display: block;
            margin-top: 4px;
            color: #555;
        }

        .note p {
            margin: 0;
            inline-size: 710px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .note .created-at {
            margin-top: 10px;
        }

        .note .category {
            margin-top: -16px;
            margin-bottom: 10px;
        }

        .note-actions {
            margin-top: 20px;
            display: block;
            text-align: right;
        }

        .note-actions a {
            font-size: 14px;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .note-actions .edit {
            color: #0f754e;
            border: 1px solid;
            border-color: #c4c4c4;
        }

        .note-actions .edit:hover {
            color: white;
            background-color: #0f754e;
            border-color: #0f754e;
        }

        .note-actions .delete {
            color: #991501;
            border: 1px solid;
            border-color: #991501;
        }

        .note-actions .delete:hover {
            color: white;
            background-color: #991501;
            border-color: #991501;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Welcome, <?php echo $_SESSION['username'] ?>!</h1>
            <a href="logout.php">Logout</a>
        </div>
        <div class="add-note-link">
            <a href="add_note.php">Add New Note</a>
        </div>

        <h3>Your Notes:</h3>
        <?php
        if ($notes) : ?>
            <?php
            foreach ($notes as $note) : ?>
                <div class="note">
                    <h2><?php echo $note['title'] ?></h2>
                    <small class="category"><?php if (!empty($note['category'])) echo "Category: " . $note['category'] ?></small>
                    <p><?php echo $note['content'] ?></p>
                    <small class="created-at">Created at: <?php echo $note['created_at'] ?></small>
                    <div class="note-actions">
                        <a href="edit_note.php?id=<?php echo $note['id'] ?>" class="edit">Edit</a>
                        <a href="delete_note.php?id=<?php echo $note['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                    </div>
                </div>
                <br>
            <?php endforeach ?>
        <?php else: ?>
            <p>You have no notes yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>