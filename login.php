<?php
session_start();

if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once 'database/connect.php';
    $sql = "SELECT * FROM `users` WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); //fetch แล้วไม่มีข้อมูล -> $row = false

    if ($row) {
        $hashed_password = $row['password'];
        $id = $row['id'];
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            header('location: index.php');
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/images/pack.png">
    <link rel="stylesheet" href="css/login.css">
    <title>Login - Note-taking App</title>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <p class="error-message">
            <?php
            if (!empty($error)) {
                echo $error;
            }
            ?>
        </p>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" value="<?php if (!empty($username)) {
                echo $username;
            }
            ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>

</html>