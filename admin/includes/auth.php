<?php
session_start();

// Check if user is logged in as admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Additional check: verify user has admin role
if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin'){
    header("Location: login.php");
    exit();
}
?>