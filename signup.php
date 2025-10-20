<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="images/logo/favicon.png" type="image/png" />
    <title>LAF System</title>
  </head>
  <body>
    <div class="center-wrapper">
      <form action="signup.php" method="POST" class="signup-container">
        <img src="images/logo/logo.jpg" alt="logo" />
        <input type="text" name="username" placeholder="Username" id="signup-username" required />
        <input type="password" name="password" placeholder="Password" id="signup-password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" id="signup-confirm-password" required />
        <input type="email" name="email" placeholder="Email" id="signup-email" required />
        <label>Already have an account? <a href="login.php">Log in</a></label>
        <button type="submit">Sign Up</button>
      </form>
    </div>
  </body>
</html>

