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
    <title>Login - Note-taking App</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #e8e8e8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h2 {
            margin-top: -3px;
            margin-bottom: 15px;
            text-align: center;
        }

        .login-container .error-message {
            text-align: center;
            font-size: 13px;
            color: #f74336;
        }

        .login-container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #4cae4c;
        }

        .login-container .register-link {
            margin-top: 20px;
            text-align: center;
        }

        .login-container .register-link p {
            font-size: 14px;
        }

        .login-container .register-link a {
            counter-reset: #5cb85c;
            text-decoration: none;
        }

        .login-container .register-link a:hover {
            text-decoration: underline;
        }
    </style>
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
            <input type="text" name="username" placeholder="Username" value="<?php if(!empty($username)) echo $username; ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>

</html>