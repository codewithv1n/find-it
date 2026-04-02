<?php
session_start();
include ('../controllers/connect_db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Find-IT - Dashboard</title>
  <link rel="stylesheet" href="css/user_main_styles.css" />
  <link rel="stylesheet" href="../plugins/css/user_sidebar_design.css">
  <link rel="stylesheet" href="../plugins/css/user_footer_design.css">
</head>
<body>

<header>
  <h1>FIND IT</h1>
  <span id="date-display"></span>

  <div class="user-actions">
    <button onclick="logout()" class="logout-btn">Logout</button>
  </div>
</header>


<?php include '../plugins/user_sidebar.php'; ?>


<div class="container">

 <div class="dashboard-card-1">
  <h1>WELCOME BACK <?php echo '$name','!' ?></h1>
  <h6>Joined since: <?php echo '$date' ?></h6>
 </div>
</div>


<?php include '../plugins/user_footer.php'; ?>


<script src="js/user_main_functions.js"></script>
</body>
</html>