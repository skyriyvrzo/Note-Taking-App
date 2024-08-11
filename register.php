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

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #e8e8e8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .register-container h2 {
            margin-top: -3px;
            margin-bottom: 15px;
            text-align: center;
        }

        .register-container .error-message {
            text-align: center;
            font-size: 13px;
            color: #f74336;
        }

        .register-container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .register-container button:hover {
            background-color: #4cae4c;
        }

        .register-container .login-link {
            margin-top: 20px;
            text-align: center;
        }

        .register-container .login-link p {
            font-size: 14px;
        }

        .register-container .login-link a {
            counter-reset: #5cb85c;
            text-decoration: none;
        }

        .register-container .login-link a:hover {
            text-decoration: underline;
        }
    </style>
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
            <input type="text" name="username" placeholder="Username" value="<?php if(!empty($username)) echo $username; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php if(!empty($email)) echo $email; ?>" required>
            <input type="password" name="password" placeholder="Password" value="<?php if(!empty($password)) echo $password; ?>" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" value="<?php if(!empty($confirm_password)) echo $confirm_password; ?>" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>