<?php
session_start();
include '../controllers/connect_db.php'; 

$user_input = $_POST['username'];
$pass_input = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $user_input); 
$stmt->execute();


$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    
    if (password_verify($pass_input, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../users/dashboard.php");
        exit();

    } else {
       header("Location: ../users/login.php?error=invalid_credentials");
       exit();
    }
}

?>