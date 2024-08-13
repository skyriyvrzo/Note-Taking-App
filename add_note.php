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
    <link rel="icon" type="image/x-icon" href="./assets/images/pack.png">
    <link rel="stylesheet" href="css/add_note.css">
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
                <div class="actions">
                    <button type="submit">Add Note</button>
                    <a href="index.php" class="cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>