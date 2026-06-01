<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config.php';

$loginMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && $pass === $admin['password']) {
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin'] = true;
    $_SESSION['role'] = 'admin'; // ✅ Add this line
    header("Location: admin.php");
    exit;
}
 else {
        $loginMessage = "❌ Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 12px;
            border: none;
        }
        .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-control {
            border-radius: 8px;
        }
        button[type="submit"] {
            background-color: #343a40;
            color: white;
            border: none;
        }
        button[type="submit"]:hover {
            background-color: #23272b;
        }
        .btn-link {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">Admin Login</h4>
                </div>
                <div class="card-body p-4">

                    <?php if (!empty($loginMessage)): ?>
                        <div class="alert alert-danger"><?= $loginMessage; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="admin_login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Admin Email</label>
                            <input type="email" name="email" id="email" class="form-control" required placeholder="Enter email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required placeholder="Enter password">
                        </div>

                        <button type="submit" class="btn w-100">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="../index.php" class="btn btn-link">← Back to Home</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
