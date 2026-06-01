<?php
session_start();
include 'config.php';
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Metro Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero {
            background: url('thumb-1920-617243.jpg') no-repeat center center;
            background-size: cover;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 1px 1px 4px #000;
        }
        .hero h1 {
            font-size: 3rem;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container">
        <a class="navbar-brand" href="#">Metro System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['reg_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="book_ticket.php">Book Ticket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin_login.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin_login.php">Admin</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="text-center">
        <h1>Welcome to Metro Management System</h1>
        <p class="lead">Book your journey faster and smarter</p>
    </div>
</div>

<!-- About Section -->
<div class="container my-5">
    <h2 class="text-black">About Metro Management System</h2>
    <p>
        The Metro Management System is a user-friendly platform that allows you to book metro tickets online with ease.
        Whether you're a daily commuter or an occasional traveler, our system helps you manage your metro journey efficiently.
        Log in or register to get started, and enjoy the convenience of digital metro access.
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
