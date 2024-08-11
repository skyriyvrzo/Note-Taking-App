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


echo $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Note-taking App</title>
</head>

<body>
    <header>
        <h1>My Notes</h1>
    </header>

    <p class="error-message">
        <?php
        if (!empty($error)) {
            echo $error;
        }
        ?>
    </p>

    <div class="container">
        <div class="note-form">
            <h2>Create a Note</h2>
            <form action="add_note.php" method="post">
                <input type="text" name="title" placeholder="Note Title" require>
                <textarea name="content" rows="5" placeholder="Note Content" require></textarea>
                <input type="text" name="category" id="Category (optional)">
                <button type="submit">Add Note</button>
            </form>
        </div>
    </div>
</body>

</html>