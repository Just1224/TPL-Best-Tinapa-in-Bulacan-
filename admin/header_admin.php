<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

@include 'includes/config.php';

$admin_id = $_SESSION['admin_id'] ?? null;

if(!isset($admin_id)){
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?> - PTL Best Tinapa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .admin-header h1 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-header nav {
            display: flex;
            gap: 20px;
        }

        .admin-header nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .admin-header nav a:hover {
            background: rgba(255,255,255,0.2);
        }

        .admin-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        .admin-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .admin-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
        }

        .admin-footer {
            background: var(--dark-color);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header class="admin-header">
    <div>
        <h1>
            <i class="fas fa-cog"></i> Admin Panel
        </h1>
    </div>
    
    <nav>
        <a href="dashboard.php" title="Dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a href="services.php" title="Services"><i class="fas fa-box"></i> Products</a>
        <a href="orders.php" title="Orders"><i class="fas fa-shopping-bag"></i> Orders</a>
        <a href="edit_content.php" title="Content"><i class="fas fa-file-alt"></i> Content</a>
        <a href="messages.php" title="Messages"><i class="fas fa-envelope"></i> Messages</a>
        <a href="payment_settings.php" title="Payment Settings"><i class="fas fa-credit-card"></i> Payments</a>
        <a href="../logout.php" title="Logout" style="background: rgba(255,0,0,0.3);"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</header>

<div class="admin-content">
