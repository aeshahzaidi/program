<?php
include 'connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email/username already exists
    $check = $conn->query("SELECT * FROM user WHERE email='$email' OR username='$username'");
    if ($check->num_rows > 0) {
        $message = "Username or Email already taken!";
    } else {
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql)) {
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .signup-container {
            background: #fff;
            padding: 30px;
            width: 380px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #1e3c72;
        }
        label {
            float: left;
            margin: 8px 0 5px;
            font-weight: bold;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #1e3c72;
            box-shadow: 0px 0px 5px rgba(30, 60, 114, 0.5);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #1e3c72;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #2a5298;
        }
        .message {
            margin-top: 10px;
            font-size: 14px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .login-link {
            margin-top: 15px;
            display: block;
            font-size: 14px;
            color: #1e3c72;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Sign Up</button>
        </form>

        <?php if (!empty($message)) { ?>
            <p class="message <?php echo (strpos($message, 'successful') !== false) ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>
</body>
</html>
