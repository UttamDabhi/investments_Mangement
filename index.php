<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Investment Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f1f3f5;
    }
    .login-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-card {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .login-card h2 {
      color: #0d6efd;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    .login-card .form-control {
      margin-bottom: 1rem;
    }
    .login-card .btn-primary {
      width: 100%;
    }
    .login-card .register-link {
      margin-top: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container login-wrapper">
    <div class="login-card">
      <h2>Login</h2>
      <form action="login.php" method="post">
        <input type="text" name="username" class="form-control" placeholder="Username" required />
        <input type="password" name="password" class="form-control" placeholder="Password" required />
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
      <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
