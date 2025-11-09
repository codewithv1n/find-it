<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LAF System</title>
    <link rel="icon" href="images/logo/favicon.png" type="image/png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>

  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <img src="images/logo/logo.jpg" alt="Logo" />
      <ul>
        <li><a href="#home" class="active"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="#search"><i class="fas fa-search"></i> Search</a></li>
        <li><a href="#posted"><i class="fas fa-file-alt"></i> Posted</a></li>
        <li><a href="#posting"><i class="fas fa-upload"></i> Posting</a></li>
        <li><a href="#about"><i class="fas fa-info-circle"></i> About</a></li>
      </ul>
      <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Home -->
    <section id="home" class="active">
      <div class="section-content">
        <h1>WELCOME TO FIND IT</h1>
        <p>
          We built this system to help people find their missing loved ones and belongings.
          This platform offers a simple, user-friendly interface with real-time updates.
        </p>

        <div class="features">
          <div class="card">
            <h3>🔎 Search Lost Items</h3>
            <p>Easily look for items or people reported missing by others.</p>
          </div>
          <div class="card">
            <h3>📢 Post Found Items</h3>
            <p>Help others by posting anything you've found.</p>
          </div>
          <div class="card">
            <h3>📡 Real-Time Updates</h3>
            <p>Stay informed with instant updates and status changes.</p>
          </div>
        </div>

        <div class="ann-image">
          <img src="images/announcement.jpg" alt="announcement" />
        </div>
      </div>
    </section>

    <!-- Search -->
    <section id="search">
      <div class="section-content center-content">
        <div class="search-box">
          <input type="text" placeholder="Search for items or people..." />
          <button><i class="fas fa-search"></i> Search</button>
        </div>
      </div>
    </section>

    <!-- Posting -->
    <section id="posting">
      <div class="section-content">
        <h2>Create a Post</h2>
        <form id="postForm">
          <div class="form-group">
            <label for="postTitle">Post Title</label>
            <input
              type="text"
              id="postTitle"
              placeholder="Enter a short title..."
              required
            />
          </div>

          <div class="form-group">
            <label for="postText">Description</label>
            <textarea
              id="postText"
              placeholder="Describe what you lost or found..."
              required
            ></textarea>
          </div>

          <div class="form-group">
             <label for="imageUpload" class="custom-button">Upload Image</label>
       <input type="file" id="imageUpload" accept="image/*" style="display:none;" />
             <div class="image-preview" id="imagePreview">
    <p>No image selected</p>
  </div>
</div>
          <button type="submit" id="postBtn">Post</button>
        </form>
      </div>
    </section>

    <!-- Posted -->
    <section id="posted">
      <div class="section-content">
        <h2>Recent Posts</h2>
        <div id="postFeed" class="post-feed">
          <p>No posts yet. Be the first to create one!</p>
        </div>
      </div>
    </section>

    <!-- About -->
    <section id="about">
      <div class="section-content">
        <h1>About</h1>
        <p><i>Created by: <b>Vinzel James Maraño | BSIT - 31002</b></i></p>
        <footer><h4>All rights reserved © 2025</h4></footer>
      </div>
    </section>

    <script src="script.js"></script>
  </body>
</html>
