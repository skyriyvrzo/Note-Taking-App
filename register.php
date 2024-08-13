<?php

if (!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        if ($password != $confirm_password) {
            $error = "Password do not match.";
        } else {
            require_once 'database/connect.php';

            $sql = "SELECT * FROM `users` WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($row['id'])) {
                $error = "Username already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "insert into users(username, email, password) values(:username, :email, :hashed_password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':hashed_password', $hashed_password);
                $stmt->execute();
                header('location: login.php');
                exit();
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Note-taking App</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/pack.png">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <p class="error-message">
            <?php
if (!empty($error)) {
    echo $error;
}
?>
        </p>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username" value="<?php if (!empty($username)) {
    echo $username;
}
?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php if (!empty($email)) {
    echo $email;
}
?>" required>
            <input type="password" name="password" placeholder="Password" value="<?php if (!empty($password)) {
    echo $password;
}
?>" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" value="<?php if (!empty($confirm_password)) {
    echo $confirm_password;
}
?>" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>