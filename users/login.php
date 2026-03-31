<?php
session_start();
include('../controllers/connect_db.php');


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Find It — Sign In</title>
  <link rel="icon" href="images/logo/favicon.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body class="auth-card">
 
  <div class="left-panel">
    <div class="dot-grid"></div>
    <div class="left-top">
      <div class="left-logo">
        <img src="images/logo/logo.jpg" alt="Find It" />
        <span>Find It.</span>
      </div>
      <h1 class="left-headline">
        Lost something?<br/>
        We'll help you<br/>
        <em>find it.</em>
      </h1>
      <p class="left-sub">
        A community-driven lost and found platform — post, search, and reconnect with what matters most.
      </p>
    </div>
    <div class="left-badges">
      <div class="left-badge"><i class="fas fa-magnifying-glass"></i><span>Search hundreds of found items</span></div>
      <div class="left-badge"><i class="fas fa-bullhorn"></i><span>Post what you've found instantly</span></div>
      <div class="left-badge"><i class="fas fa-bolt"></i><span>Real-time community updates</span></div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <div class="form-box">
      <div class="form-box-header">
        <h2>Welcome back</h2>
        <p>Sign in to your Find It account to continue.</p>
      </div>

      <?php if (!empty($errorMsg)): ?>
        <div class="error-msg">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo htmlspecialchars($errorMsg); ?>
        </div>
      <?php endif; ?>

      <form action="../controllers/user_login_process.php" method="POST">
        <label class="field-label" for="username">Username</label>
        <div class="auth-field">
          <input type="text" id="username" name="username" placeholder="Your username" autocomplete="username" required />
          <i class="fas fa-user f-icon"></i>
        </div>

        <label class="field-label" for="password">Password</label>
        <div class="auth-field">
          <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password" required />
          <i class="fas fa-lock f-icon"></i>
        </div>

        <button type="submit" class="btn-auth">
          Sign In<i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <div class="or-line">or</div>
      <p class="auth-footer">
        Don't have an account?<a href="signup.php"> Sign up</a>
      </p>
    </div>
  </div>

</body>
</html>