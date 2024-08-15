<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

require_once 'database/connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: index.php');
    exit();
}

$note_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

if (!empty($_POST)) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    if (!empty($title) && !empty($content)) {
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
} 
else {
    $sql = "SELECT * FROM notes WHERE id = :id AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $note_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$note) {
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
    <title>Edit - Note-taking App</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/pack.png">
    <link rel="stylesheet" href="css/edit_note.css">
</head>

<body>
    <div class="edit-note-container">
        <h2>Edit Note</h2>
        <form action="edit_note.php?id=<?php echo $note_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" value="<?php echo $note['title'] ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea type="text" name="content" required><?php echo $note['content'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" , value="<?php echo $note['category'] ?>">
            </div>
            <div class="actions">
                <button type="submit">Save Changes</button>
                <a href="index.php" class="cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>