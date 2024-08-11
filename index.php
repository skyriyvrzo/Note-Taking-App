<?php 

session_start();

if(!isset($_SESSION['user_id'])) {
    header('location: login.html');
    exit();
}

require_once 'database/connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notes WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>