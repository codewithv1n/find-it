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
  <title>Find IT - Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/user_main_styles.css" />
  <link rel="stylesheet" href="../plugins/css/user_sidebar_design.css">
  <link rel="stylesheet" href="../plugins/css/user_footer_design.css">
</head>
<body>

<header>
  <h1>FIND IT</h1>

<div class="user-info">
  <i class="fa fa-user-circle-o">
   <h5></h5>
  </i>
</div>
  
   <div class="notification-bell">
     <i class="fa fa-bell"><?php ?></i>
   </div>

  <div class="user-actions">
    <button onclick="logout()" class="logout-btn">Logout</button>
  </div>
</header>


<?php include '../plugins/user_sidebar.php'; ?>


<div class="container">

 <div class="dashboard-card-1">
    <h1>WELCOME BACK,<?php echo 'codewithv1n','!' ?></h1>
     <div class="dashboard-card1-content" >
        <span><i class="fa fa-calendar"></i>Thursday, April 2, 2026</span>
        <span><i class="fa fa-history"></i>Joined since: September 19,2022</span>
     </div>
 </div>
 


  <div class="recent-posting-card">
   <h2 class="recent-header">
        <i class="fa fa-history"></i> Recent Item Posting
    </h2>
     
    <div class="posted-data-container">
      <div class="posted-data">
        <i class="fa fa-user-circle-o"></i><h6>codewithv1n</h6>
        <p>Descriptions: </p>
        <p>Location: </p>
      </div>
    </div>

    <div class="posted-data-container">
      <div class="posted-data">
        <i class="fa fa-user-circle-o"></i><h6>codewithv1n</h6>
        <p>Descriptions: </p>
        <p>Location: </p>
      </div>
    </div>
  
    <div class="posted-data-container">
      <div class="posted-data">
        <i class="fa fa-user-circle-o"></i><h6>codewithv1n</h6>
        <p>Descriptions: </p>
        <p>Location: </p>
      </div>
    </div>
  
  
  </div> 
  


  
</div>


<?php include '../plugins/user_footer.php'; ?>


<script src="js/user_main_functions.js"></script>
</body>
</html>