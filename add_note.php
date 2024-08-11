<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

if (!empty($_POST)) {
    $username = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    if (!empty($title) && !empty($content)) {
        require_once 'database/connect.php';
        $sql = "INSERT INTO notes (user_id, title, content, category) VALUES (:user_id, :title, :content, :category)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $username);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        header('location: index.php');
        exit();
    } else {
        $error = "Please fill in both the title and content.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Note - Note-taking App</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #e8e8e8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        .note-form {
            margin-bottom: 20px;
        }

        .note-form input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .note-form button {
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: while;
            cursor: pointer;
        }

        .note-form button:hover {
            background-color: #4cae4c;
        }

        .error-message {
            color: red;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <header>
        <h1>My Notes</h1>
    </header>

    <div class="container">
        <div class="note-form">
            <h2>Create a Note</h2>
            <p class="error-message">
                <?php
                if (!empty($error)) {
                    echo $error;
                }
                ?>
            </p>
            <form action="add_note.php" method="post">
                <input type="text" name="title" placeholder="Note Title" required>
                <textarea name="content" rows="5" placeholder="Note Content" required></textarea>
                <input type="text" name="category" placeholder="Category (optional)">
                <button type="submit">Add Note</button>
            </form>
        </div>
    </div>
</body>

</html>