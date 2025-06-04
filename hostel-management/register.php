<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Hostel Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #219150;
        }

        .captcha {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>User Registration</h2>
    <form method="POST" action="register_process.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>User Type:</label>
        <select name="user_type" required>
            <option value="Student">Student</option>
            <option value="Admin">Admin</option>
            <option value="Caretaker">Caretaker</option>
        </select>

        <div class="captcha">
            <!-- reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LcYnQsrAAAAAKHr5ffU0ZzKkruUiaNe_6hJHXd6"></div>
        </div>

        <button type="submit">Register</button>
    </form>
</div>

<!-- reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>
