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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="images/logo/favicon.png" type="image/png" />
     <link rel="stylesheet" href="styles.css" />
     <script src="script.js" defer></script>
    <title>LAF System</title>
  </head>
  <body>
    <div class="sidebar">
  <ul>
    <img src="images/logo/logo.jpg" alt="logo" />
    <li><a href="#" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
    <li><a href="#" onclick="showSection('search')"><i class="fas fa-search"></i> Search</a></li>
    <li><a href="#" onclick="showSection('posted')"><i class="fas fa-file-alt"></i> Posted</a></li>
    <li><a href="#" onclick="showSection('posting')"><i class="fas fa-upload"></i> Posting</a></li>
    <li><a href="#" onclick="showSection('about')"><i class="fas fa-info-circle"></i> About</a></li>
    <a href="login.php" class="logout-btn"> <i class="fas fa-sign-out-alt"></i> Logout</a>
  </ul>
</div>


    <section id="home" class="active">
      <div class="home-section">
        <h1>WELCOME TO LOST AND FOUND SYSTEM</h1>
        <p>
          "We built this system to help people find their missing love ones and
          things this system<br />brings you a easier to use and realtime
          updates."
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

    <section id="search">
      <div class="search-section">
        <input type="text" placeholder="Search" class="search-input" />
        <button class="search-button">Search</button>
      </div>
    </section>

    <section id="posting">
      <div class="posting-container">
        <h2>Create a Post</h2>
        <textarea id="postText" placeholder="What's on your mind?"></textarea
        ><br />
        <input type="file" id="imageUpload" accept="image/*" /><br />
        <button id="postBtn">Post</button>
      </div>
    </section>

    <section id="posted">
      <div id="postFeed"></div>
    </section>

    <section id="about">
      <div class="about-section">
        <h1>WELCOME TO ABOUT SECTION</h1>
        <p>
          <i>This is the system made by: Vinzel James Maraño | BSIT - 31002</i>
        </p>

        <footer><h1>All rights reserve 2025</h1></footer>
      </div>
    </section>

    
  </body>
</html>
