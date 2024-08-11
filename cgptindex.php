<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notetakingapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT id, title, content, created_at FROM notes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
            padding: 20px;
        }
        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .note {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .note h3 {
            margin-top: 0;
        }
        .note p {
            margin: 0;
        }
        .note-actions {
            margin-top: 10px;
        }
        .note-actions a {
            color: #5cb85c;
            text-decoration: none;
            margin-right: 10px;
        }
        .note-actions a:hover {
            text-decoration: underline;
        }
        .logout {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="add_note.php">Add New Note</a>
        <h2>Your Notes</h2>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="note">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small>Created at: <?php echo $row['created_at']; ?></small>
                <div class="note-actions">
                    <a href="edit_note.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_note.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                </div>
            </div>
        <?php } ?>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
