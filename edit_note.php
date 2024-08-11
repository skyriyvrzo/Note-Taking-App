<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

require_once 'database/connect.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: index.php');
    exit();
}

$note_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

if(!empty($_POST)) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    if(!empty($title) && !empty($content)) {
        $sql = "UPDATE notes SET title = :title, content = :content, category = :category WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':id', $note_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        header('location: index.php');
        exit();
    }
} else {
    $sql = "SELECT * FROM notes WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $note_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$note) {
        header('location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .edit-note-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            height: 150px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="edit-note-container">
        <h2>Edit Note</h2>
        <form action="edit_note.php?id=<?php echo $note_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" value="<?php echo $note['title']?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea type="text" name="content" required><?php echo $note['content']?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category", value="<?php echo $note['category']?>" required>
            </div>
            <button type="submit">Update Note</button>
        </form>
    </div>
</body>
</html>