<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Gorkha Institute of Technology</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <!-- Left: Logo and University Name - Clickable to Student Home -->
            <a href="/course_hub/student/index.php" class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>Gorkha Institute of Technology</h1>
                    <span>Est. 2024 | Excellence in Education</span>
                </div>
            </a>
            
            <!-- Center: Navigation Links for Admin -->
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="programmes.php"><i class="fas fa-book-open"></i> Programmes</a></li>
                    <li><a href="modules.php"><i class="fas fa-book"></i> Modules</a></li>
                    <li><a href="staff.php"><i class="fas fa-users"></i> Staff</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="/course_hub/student/index.php"><i class="fas fa-home"></i> Student Home</a></li>
                </ul>
            </nav>
            
            <!-- Right: Admin Info & Logout -->
            <div class="auth-buttons">
                <span class="admin-welcome"><i class="fas fa-crown"></i> Admin</span>
                <a href="logout.php" class="btn-auth logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
        </div>
    </header>
    <main>