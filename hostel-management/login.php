<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Hostel Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .container-fluid {
      height: 100vh;
      display: flex;
      flex-direction: row;
    }

    .image-side {
      flex: 0.8;
      background-image: url('uploads/sakthi-hostel.jpg');
      background-size: cover;
      background-position: center;
      animation: slideInLeft 1s ease-out forwards;
    }

    .form-side {
      flex: 1.2;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(to bottom right, #f0f4f8, #d9e2ec);
      background-image: url('uploads/blur-hostel.jpg');
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .form-side::before {
      content: "";
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(4px);
      z-index: 1;
    }

    .login-form {
      width: 100%;
      max-width: 400px;
      z-index: 2;
      padding: 40px;
      border-radius: 15px;
      background: white;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 {
      font-weight: 700;
      margin-bottom: 30px;
      color: #343a40;
      text-align: center;
    }

    .form-control, .form-select {
      border-radius: 12px;
      height: 45px;
      margin-bottom: 20px;
    }

    .form-control:focus, .form-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .login-btn {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      background: linear-gradient(to right, #0072ff, #00c6ff);
      border: none;
      color: white;
      font-weight: 600;
      transition: all 0.3s;
    }

    .login-btn:hover {
      transform: scale(1.03);
      background: linear-gradient(to right, #00c6ff, #0072ff);
    }

    .captcha {
      text-align: center;
      margin: 20px 0;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-100px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(100px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @media (max-width: 768px) {
      .container-fluid {
        flex-direction: column;
      }

      .image-side {
        height: 200px;
        width: 100%;
      }

      .form-side {
        flex: none;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="image-side"></div>
  <div class="form-side">
    <div class="login-form">
      <h2><i class="fas fa-user-shield me-2"></i>Login</h2>
      <form action="login-check.php" method="POST">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
        <input type="text" class="form-control" name="phone" placeholder="Phone" required>
        <input type="password" class="form-control" name="password" placeholder="Password" required>

        <select class="form-select" name="usertype" id="usertype" required>
          <option value="">Select User Type</option>
          <option value="Student">Student</option>
          <option value="Admin">Admin</option>
          <option value="Caretaker">Caretaker</option>
        </select>

        <div class="captcha">
          <div class="g-recaptcha" data-sitekey="6LcYnQsrAAAAAKHr5ffU0ZzKkruUiaNe_6hJHXd6"></div>
        </div>

        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>
