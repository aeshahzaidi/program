<?php
session_start();
include 'connect.php';



$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM user WHERE email='$email'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php"); // redirect to homepage
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No account found with that email!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>UTeM Program List - Login</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        h2 {
            margin-bottom: 25px;
            color: #1e3c72;
            font-size: 22px;
        }
        label {
            float: left;
            margin: 8px 0 5px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }
        input:focus {
            border-color: #1e3c72;
            box-shadow: 0px 0px 6px rgba(30, 60, 114, 0.4);
        }
        button {
            width: 100%;
            padding: 14px;
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
        .error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
        .signup-link {
            margin-top: 20px;
            display: block;
            font-size: 14px;
            color: #1e3c72;
        }
        .signup-link:hover {
            text-decoration: underline;
        }
        
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-title">UTeM Program List</div>
        <div class="subtitle">Please login to continue</div>
        
        <h2>Login</h2>
        <form method="post">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Login</button>
        </form>
        
        <?php if (!empty($message)) { ?>
            <p class="error"><?php echo $message; ?></p>
        <?php } ?>
        
        <a href="signup.php" class="signup-link">Don’t have an account? Sign Up</a>
        
        <div class="footer">© <?php echo date("Y"); ?> Universiti Teknikal Malaysia Melaka (UTeM)</div>
    </div>
</body>
</html>
