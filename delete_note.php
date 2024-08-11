<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

require_once 'database/connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: index.php');
    exit();
}

$note_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM notes WHERE id = :id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $note_id);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

header('location: index.php');
exit();
?>