<?php
session_start();
include('../controllers/connect_db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = ($_SESSION['user_id'] === 'admin');
$userName = '';

// Fetch user info (if regular user)
if (!$isAdmin) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
}

// Get statistics from database
function getStats($conn) {
    $stats = ['lost' => 0, 'found' => 0, 'users' => 0, 'resolved' => 0];

    // Lost items count
    $result = $conn->query("SELECT COUNT(*) as cnt FROM items WHERE type='lost'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['lost'] = $row['cnt'];
    }

    // Found items count
    $result = $conn->query("SELECT COUNT(*) as cnt FROM items WHERE type='found'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['found'] = $row['cnt'];
    }

    // Users count (excluding admin)
    $result = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE id != 'admin'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['users'] = $row['cnt'];
    }

    // Resolved items (assuming resolved = items with status 'resolved' or matched)
    $result = $conn->query("SELECT COUNT(*) as cnt FROM items WHERE status='resolved'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['resolved'] = $row['cnt'];
    }

    return $stats;
}

$stats = getStats($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Find It — Dashboard</title>
  <link rel="icon" href="images/logo/favicon.png" type="image/png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-brand">
    <img src="images/logo/logo.jpg" alt="Find It logo" />
    <h2>Find It.</h2>
    <span>Lost &amp; Found System</span>
  </div>

  <div class="nav-label">Menu</div>
  <nav>
    <ul>
      <li><a href="#" class="nav-link active" data-section="home"><i class="fas fa-house"></i> Home</a></li>
      <li><a href="#" class="nav-link" data-section="search"><i class="fas fa-magnifying-glass"></i> Search</a></li>
      <li><a href="#" class="nav-link" data-section="posted"><i class="fas fa-layer-group"></i> Posted Items</a></li>
      <li><a href="#" class="nav-link" data-section="posting"><i class="fas fa-plus-circle"></i> Create Post</a></li>
      <li><a href="#" class="nav-link" data-section="about"><i class="fas fa-circle-info"></i> About</a></li>
    </ul>
  </nav>

  <div class="sidebar-user">
    <div class="user-avatar">
      <?php echo $isAdmin ? 'A' : strtoupper(substr($userName ?: (string)$_SESSION['user_id'], 0, 1)); ?>
    </div>
    <div class="user-info">
      <strong><?php echo $isAdmin ? 'Admin' : htmlspecialchars($userName ?: 'User'); ?></strong>
      <span><?php echo $isAdmin ? 'Administrator' : 'Member'; ?></span>
    </div>
  </div>

  <a href="logout.php" class="logout-btn">
    <i class="fas fa-arrow-right-from-bracket"></i> Sign Out
  </a>
</div>

<!-- MAIN CONTENT -->
<div class="main">

  <div class="topbar">
    <span class="topbar-title" id="topbarTitle">Home</span>
    <span class="topbar-badge"><i class="fas fa-circle" style="font-size:0.45rem;"></i>&nbsp; Live</span>
  </div>

  <!-- HOME SECTION -->
  <section id="home" class="active">
    <div class="page-header">
      <h1>Welcome back, <?php echo $isAdmin ? 'Admin' : htmlspecialchars($userName ?: 'User'); ?> 👋</h1>
      <p>Here's what's happening on the Find It platform today.</p>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--accent-dim);color:var(--accent);"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-value"><?php echo $stats['lost']; ?></div>
        <div class="stat-label">Lost Reports</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--green-dim);color:var(--green);"><i class="fas fa-circle-check"></i></div>
        <div class="stat-value"><?php echo $stats['found']; ?></div>
        <div class="stat-label">Found Items</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--blue-dim);color:var(--blue);"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?php echo $stats['users']; ?></div>
        <div class="stat-label">Users</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fef9e7;color:#b7860d;"><i class="fas fa-handshake"></i></div>
        <div class="stat-value"><?php echo $stats['resolved']; ?></div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>

    <div class="content-card">
      <p style="font-size:0.88rem;color:var(--ink-mid);line-height:1.75;margin-bottom:18px;">
        <strong style="color:var(--ink-dark);">Find It</strong> helps people reconnect with their lost belongings and loved ones.
        Post what you've lost or found, search community reports, and make a difference.
      </p>
      <div class="features">
        <div class="feature-card">
          <div class="f-icon" style="background:var(--accent-dim);color:var(--accent);"><i class="fas fa-magnifying-glass"></i></div>
          <h3>Search Reports</h3>
          <p>Browse all lost and found reports instantly in one place.</p>
        </div>
        <div class="feature-card">
          <div class="f-icon" style="background:var(--green-dim);color:var(--green);"><i class="fas fa-bullhorn"></i></div>
          <h3>Post Found Items</h3>
          <p>Help someone — post what you've found right away.</p>
        </div>
        <div class="feature-card">
          <div class="f-icon" style="background:var(--blue-dim);color:var(--blue);"><i class="fas fa-bolt"></i></div>
          <h3>Real-Time Updates</h3>
          <p>Posts go live instantly so anyone nearby can act fast.</p>
        </div>
      </div>
      <div class="ann-image">
        <img src="images/announcement.jpg" alt="Announcement" />
      </div>
    </div>
  </section>

  <!-- SEARCH SECTION -->
  <section id="search">
    <div class="page-header">
      <h1>Search</h1>
      <p>Find lost or found item reports from the community.</p>
    </div>
    <div class="content-card">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="e.g. black wallet, brown dog, iPhone…" />
        <button id="searchBtn"><i class="fas fa-magnifying-glass"></i> Search</button>
      </div>
      <div class="search-results" id="searchResults"></div>
    </div>
  </section>

  <!-- CREATE POST SECTION -->
  <section id="posting">
    <div class="page-header">
      <h1>Create a Post</h1>
      <p>Report something lost or log something you've found.</p>
    </div>
    <div class="content-card">
      <div class="post-form">
        <form id="postForm" class="form-grid">
          <div class="field-group">
            <label>Post Type</label>
            <div class="type-toggle">
              <button type="button" class="type-btn sel-lost" id="btnLost" onclick="setType('lost')">
                <i class="fas fa-triangle-exclamation"></i> Lost
              </button>
              <button type="button" class="type-btn" id="btnFound" onclick="setType('found')">
                <i class="fas fa-circle-check"></i> Found
              </button>
            </div>
            <input type="hidden" id="postType" value="lost" />
          </div>

          <div class="field-group">
            <label for="postTitle">Title</label>
            <input type="text" id="postTitle" placeholder="e.g. Lost black leather wallet near SM…" required />
          </div>

          <div class="field-group">
            <label for="postText">Description</label>
            <textarea id="postText" placeholder="Color, brand, location, date — the more detail the better." required></textarea>
          </div>

          <div class="field-group">
            <label>Photo (optional)</label>
            <label for="imageUpload" class="upload-area">
              <i class="fas fa-cloud-arrow-up"></i>
              <p>Drag & drop or <span>browse files</span></p>
              <p style="font-size:0.71rem;margin-top:3px;">PNG, JPG up to 5 MB</p>
            </label>
            <input type="file" id="imageUpload" accept="image/*" style="display:none;" />
            <div id="imagePreview"></div>
          </div>

          <div>
            <button type="submit" class="btn-post">
              <i class="fas fa-paper-plane"></i> Publish Post
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- POSTED ITEMS SECTION -->
  <section id="posted">
    <div class="page-header">
      <h1>Posted Items</h1>
      <p>All active lost and found reports from the community.</p>
    </div>
    <div class="content-card">
      <div id="postFeed" class="post-feed">
        <div class="empty-state">
          <i class="fas fa-inbox"></i>
          <p>No posts yet. Be the first to create one!</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT SECTION -->
  <section id="about">
    <div class="page-header">
      <h1>About</h1>
      <p>Learn more about this project.</p>
    </div>
    <div class="content-card">
      <div class="about-grid">
        <div class="about-block">
          <h3>What is Find It?</h3>
          <p>Find It is a community-driven Lost &amp; Found platform designed to help people quickly report and discover missing items or persons. Posts go live instantly and are searchable by anyone.</p>
        </div>
        <div class="about-block">
          <h3>How it works</h3>
          <p>Create a post tagged as "Lost" or "Found", describe the item with a photo, and the community can search and reach out to return or reclaim it.</p>
        </div>
      </div>

      <div class="creator-chip">
        <div class="creator-avatar">V</div>
        <div class="creator-info">
          <strong>Vinzel James Maraño</strong>
          <span>BSIT – 31002 &nbsp;·&nbsp; Developer</span>
        </div>
      </div>

      <div class="about-footer">All rights reserved &copy; 2025 &nbsp;·&nbsp; Find It System</div>
    </div>
  </section>

</div>

<!-- JAVASCRIPT FOR INTERACTIVITY -->
<script>
  // Section switching
  const sections = document.querySelectorAll('section');
  const navLinks = document.querySelectorAll('.nav-link');
  const topbarTitle = document.getElementById('topbarTitle');

  function showSection(sectionId) {
    sections.forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    navLinks.forEach(link => link.classList.remove('active'));
    document.querySelector(`.nav-link[data-section="${sectionId}"]`).classList.add('active');
    topbarTitle.textContent = document.querySelector(`.nav-link[data-section="${sectionId}"]`).textContent.trim();
  }

  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const section = link.dataset.section;
      showSection(section);
    });
  });

  // Post type toggle
  function setType(type) {
    document.getElementById('postType').value = type;
    const btnLost = document.getElementById('btnLost');
    const btnFound = document.getElementById('btnFound');
    if (type === 'lost') {
      btnLost.classList.add('sel-lost');
      btnFound.classList.remove('sel-lost');
    } else {
      btnFound.classList.add('sel-lost');
      btnLost.classList.remove('sel-lost');
    }
  }

  // Image upload preview
  document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = `<img src="${ev.target.result}" alt="Preview" style="max-width:200px; max-height:200px; margin-top:10px;">`;
      };
      reader.readAsDataURL(file);
    }
  });

  // Search functionality (mock)
  document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value.trim();
    if (query === '') {
      alert('Please enter a search term.');
      return;
    }
    // Simulate search results (replace with actual AJAX)
    document.getElementById('searchResults').innerHTML = '<p>Searching...</p>';
    setTimeout(() => {
      document.getElementById('searchResults').innerHTML = '<p>No results found for "' + query + '".</p>';
    }, 1000);
  });

  // Form submission (mock)
  document.getElementById('postForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Post created successfully! (Demo mode)');
    // Reset form
    this.reset();
    setType('lost');
    document.getElementById('imagePreview').innerHTML = '';
  });

  // Load posted items (mock)
  function loadPostedItems() {
    // This would be an AJAX call to fetch posts
    const feed = document.getElementById('postFeed');
    feed.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>No posts yet. Be the first to create one!</p></div>';
  }
  loadPostedItems();
</script>

</body>
</html>