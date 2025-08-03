<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username not found.";
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
    <title>LAF System</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="icon" href="images/logo/favicon.png" type="image/png" />
    <script src="script.js" defer></script>
  </head>
  <body>

    <form action="login.php" method="POST">
      <div class="login-container">
      <img src="images/logo/logo.jpg" alt="logo" />
      <input type="text" name="username" placeholder="Username" id="username" />
      <input type="password" name="password" placeholder="Password" id="password" />
      <label>Don't have an account?<a href="signup.php">Sign Up</a></label>
      <button type="submit">Login</button>
    </div>

    </form>
   
  </body>
</html>
