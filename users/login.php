<?php
session_start();
include('../controllers/connect_db.php');

$errorMsg = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Admin login
    if ($username === "admin" && $password === "1234") {
        $_SESSION['user_id'] = 'admin';
        $_SESSION['username'] = 'admin';
        header("Location: index.php");
        exit();
    }

    // Regular user login
    $errorMsg = authenticateUser($conn, $username, $password);
}

/**
 * Authenticate user credentials
 */
function authenticateUser($conn, $username, $password) {
    if (empty($username) || empty($password)) {
        return "Please enter username and password.";
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return "Invalid username or password.";
    }

    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();
    
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    }
    
    $stmt->close();
    return "Invalid username or password.";
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
  <!-- LEFT PANEL -->
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

      <form action="login.php" method="POST" novalidate>
        <label class="field-label" for="username">Username</label>
        <div class="auth-field">
          <input
            type="text" id="username" name="username"
            placeholder="Your username"
            autocomplete="username"
            value="<?php echo htmlspecialchars($username); ?>"
            required
          />
          <i class="fas fa-user f-icon"></i>
        </div>

        <label class="field-label" for="password">Password</label>
        <div class="auth-field">
          <input
            type="password" id="password" name="password"
            placeholder="Your password"
            autocomplete="current-password"
            required
          />
          <i class="fas fa-lock f-icon"></i>
          <button type="button" class="toggle-pw" onclick="togglePw('password','eyeIcon')" aria-label="Toggle password">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>

        <button type="submit" class="btn-auth">
          Sign In &nbsp;<i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <div class="or-line">or</div>
      <p class="auth-footer">
        Don't have an account?<a href="signup.php">Create one free</a>
      </p>
    </div>
  </div>

  <script>
    function togglePw(id, iconId) {
      const input = document.getElementById(id);
      const icon = document.getElementById(iconId);
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
    }
  </script>
</body>
</html>