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
    <link rel="stylesheet" href="css/index.css">
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
if ($notes): ?>
            <?php
foreach ($notes as $note): ?>
                <div class="note">
                    <h2><?php echo $note['title'] ?></h2>
                    <small class="category"><?php if (!empty($note['category'])) {
    echo "Category: " . $note['category'];
}
?></small>
                    <p><?php echo $note['content'] ?></p>
                    <small class="created-at">Created at: <?php echo $note['created_at'] ?></small>
                    <div class="note-actions">
                        <a href="edit_note.php?id=<?php echo $note['id'] ?>" class="edit">Edit</a>
                        <a href="delete_note.php?id=<?php echo $note['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                    </div>
                </div>
                <br>
            <?php endforeach?>
        <?php else: ?>
            <p>You have no notes yet.</p>
        <?php endif;?>
    </div>
</body>

</html>