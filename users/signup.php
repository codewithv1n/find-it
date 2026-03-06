<?php
include('../controllers/connect_db.php');

$notif = '';
$formData = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'username' => trim($_POST['username'] ?? ''),
        'email' => trim($_POST['email'] ?? '')
    ];
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $notif = validateAndCreateUser($conn, $formData, $password, $confirm);
    
    if (strpos($notif, 'success') === 0) {
        $formData = ['username' => '', 'email' => ''];
    }
}

/* Validate input and create new user */
function validateAndCreateUser($conn, $formData, $password, $confirm) {
    // Validation
    if (in_array('', $formData) || empty($password) || empty($confirm)) {
        return 'error:All fields are required.';
    }
    
    if (strlen($formData['username']) < 3) {
        return 'error:Username must be at least 3 characters.';
    }
    
    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        return 'error:Please enter a valid email address.';
    }
    
    if (strlen($password) < 6) {
        return 'error:Password must be at least 6 characters.';
    }
    
    if ($password !== $confirm) {
        return 'error:Passwords do not match.';
    }

    // Check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $formData['username'], $formData['email']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        return 'error:Username or email is already taken.';
    }
    $check->close();

    // Create user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $formData['username'], $hashed, $formData['email']);

    if ($stmt->execute()) {
        $stmt->close();
        return 'success:Account created! Redirecting…';
    }
    
    $stmt->close();
    return 'error:Something went wrong. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Find It — Create Account</title>
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
        Join the<br/>
        community.<br/>
        <em>Help others.</em>
      </h1>
      <p class="left-sub">
        Create your free account and start helping people find what they've lost.
      </p>
    </div>
    <div class="left-steps">
      <div class="left-step">
        <div class="step-num">1</div>
        <div class="step-text">
          <strong>Create your account</strong>
          <span>Takes less than a minute</span>
        </div>
      </div>
      <div class="left-step">
        <div class="step-num">2</div>
        <div class="step-text">
          <strong>Browse lost & found posts</strong>
          <span>Search by item name or description</span>
        </div>
      </div>
      <div class="left-step">
        <div class="step-num">3</div>
        <div class="step-text">
          <strong>Post what you've found</strong>
          <span>Help reunite items with their owners</span>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <div class="form-box">
      <div class="form-box-header">
        <h2>Create an account</h2>
        <p>Free forever. No credit card needed.</p>
      </div>

      <?php if (!empty($notif)): 
        [$type, $message] = explode(':', $notif, 2);
        $icon = $type === 'success' ? 'fa-circle-check' : 'fa-exclamation-circle';
      ?>
        <div class="alert <?php echo $type; ?>">
          <i class="fas <?php echo $icon; ?>"></i>
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form action="signup.php" method="POST" novalidate>
        <div class="field-row">
          <div>
            <label class="field-label" for="username">Username</label>
            <div class="auth-field">
              <input
                type="text" id="username" name="username"
                placeholder="e.g. jdoe"
                autocomplete="username"
                value="<?php echo htmlspecialchars($formData['username']); ?>"
                required
              />
              <i class="fas fa-user f-icon"></i>
            </div>
          </div>
          <div>
            <label class="field-label" for="email">Email</label>
            <div class="auth-field">
              <input
                type="email" id="email" name="email"
                placeholder="you@email.com"
                autocomplete="email"
                value="<?php echo htmlspecialchars($formData['email']); ?>"
                required
              />
              <i class="fas fa-envelope f-icon"></i>
            </div>
          </div>
        </div>

        <label class="field-label" for="password">Password</label>
        <div class="auth-field">
          <input
            type="password" id="password" name="password"
            placeholder="Min. 6 characters"
            autocomplete="new-password"
            required
          />
          <i class="fas fa-lock f-icon"></i>
          <button type="button" class="toggle-pw" onclick="togglePw('password','eye1')">
            <i class="fas fa-eye" id="eye1"></i>
          </button>
        </div>
        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
        <p class="strength-hint" id="strengthHint">Use 6+ characters for a stronger password.</p>

        <label class="field-label" for="confirm">Confirm Password</label>
        <div class="auth-field">
          <input
            type="password" id="confirm" name="confirm_password"
            placeholder="Repeat your password"
            autocomplete="new-password"
            required
          />
          <i class="fas fa-lock f-icon"></i>
          <button type="button" class="toggle-pw" onclick="togglePw('confirm','eye2')">
            <i class="fas fa-eye" id="eye2"></i>
          </button>
        </div>

        <p class="terms-note">
          By creating an account, you agree to help the community with honesty and good faith.
        </p>

        <button type="submit" class="btn-auth">
          Create Account &nbsp;<i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <div class="or-line">or</div>
      <p class="auth-footer">
        Already have an account?<a href="login.php">Sign in</a>
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

    // Password strength meter
    const pwInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthHint = document.getElementById('strengthHint');

    const levels = [
      { max: 0, width: '0%', color: '#e2ddd7', text: 'Use 6+ characters for a stronger password.' },
      { max: 2, width: '25%', color: '#e05a1e', text: 'Weak — try adding numbers or symbols.' },
      { max: 4, width: '55%', color: '#e09c1e', text: 'Fair — a bit longer would help.' },
      { max: 6, width: '78%', color: '#4a9e6b', text: 'Good password.' },
      { max: 99, width: '100%', color: '#1a8a4a', text: 'Strong password!' }
    ];

    function checkPasswordStrength(password) {
      if (!password) return 0;
      let score = 0;
      if (password.length >= 6) score++;
      if (password.length >= 10) score++;
      if (/[A-Z]/.test(password)) score++;
      if (/[0-9]/.test(password)) score++;
      if (/[^A-Za-z0-9]/.test(password)) score++;
      if (password.length >= 14) score++;
      return score;
    }

    pwInput.addEventListener('input', () => {
      const score = checkPasswordStrength(pwInput.value);
      const level = levels.find(l => score <= l.max) || levels[levels.length - 1];
      
      strengthFill.style.width = pwInput.value ? level.width : '0%';
      strengthFill.style.backgroundColor = level.color;
      strengthHint.textContent = level.text;
    });

    <?php if (!empty($notif) && strpos($notif, 'success:') === 0): ?>
      setTimeout(() => window.location.href = 'login.php', 1800);
    <?php endif; ?>
  </script>
</body>
</html>