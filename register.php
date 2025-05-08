<?php
require 'inc/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?);");
    $stmt->execute([$username, $password]);
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Investment Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f1f3f5;
    }
    .register-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .register-card {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .register-card h2 {
      color: #0d6efd;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    .register-card .form-control {
      margin-bottom: 1rem;
    }
    .register-card .btn-primary {
      width: 100%;
    }
    .register-card .login-link {
      margin-top: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container register-wrapper">
    <div class="register-card">
      <h2>Register</h2>
      <form method="post" action="register.php">
        <input type="text" name="username" class="form-control" placeholder="Username" required />
        <input type="password" name="password" class="form-control" placeholder="Password" required />
        <button type="submit" class="btn btn-primary">Register</button>
      </form>
      <p class="login-link">Already have an account? <a href="index.php">Login</a></p>
    </div>
  </div>
</body>
</html>

