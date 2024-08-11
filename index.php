<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.html');
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
</head>

<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo $_SESSION['username'] ?>!</h1>
        <a href="add_note.php">Add New Note</a>
        <?php
        if ($notes) : ?>
            <?php
            foreach ($notes as $note) : ?>
                <div class="note">
                    <h3>
                        <?php echo $note['title'] ?>
                    </h3>

                    <small>
                        <?php
                        if (!empty($note['category']))
                            echo "Category: " . $note['category']
                        ?>
                    </small>

                    <p>
                        <?php echo $note['content'] ?>
                    </p>

                    <small>
                        <?php echo $note['created_at'] ?>
                    </small>
                </div>
                <br>
            <?php endforeach ?>
        <?php else: ?>
            <p>You have no notes yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>