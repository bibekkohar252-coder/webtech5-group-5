<?php
$connection = mysqli_connect('localhost', 'root', '', 'student_course_hub');
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIT - Gorkha Institute of Technology</title>
    <link rel="stylesheet" href="/course_hub/student/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    <header>
        <div class="header-container">
            <a href="/course_hub/student/index.php" class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>Gorkha Institute of Technology</h1>
                    <span>Est. 2024 | Excellence in Education</span>
                </div>
            </a>
            <nav>
                <ul>
                    <li><a href="/course_hub/student/index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="/course_hub/student/programmes.php?level=1"><i class="fas fa-graduation-cap"></i> Undergraduate</a></li>
                    <li><a href="/course_hub/student/programmes.php?level=2"><i class="fas fa-university"></i> Postgraduate</a></li>
                    <li><a href="/course_hub/student/modules.php"><i class="fas fa-book"></i> Modules</a></li>
                    <li><a href="/course_hub/student/staff.php"><i class="fas fa-users"></i> Our Staff</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true): ?>
                    <a href="/course_hub/staff/dashboard.php" class="btn-auth staff"><i class="fas fa-chalkboard-user"></i> Staff Dashboard</a>
                    <a href="/course_hub/staff/logout.php" class="btn-auth logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="/course_hub/staff/" class="btn-auth staff"><i class="fas fa-user-tie"></i> Staff Login</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="/course_hub/admin/dashboard.php" class="btn-auth admin"><i class="fas fa-crown"></i> Admin Panel</a>
                    <a href="/course_hub/admin/logout.php" class="btn-auth logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="/course_hub/admin/" class="btn-auth admin"><i class="fas fa-lock"></i> Admin Login</a>
                <?php endif; ?>
            </div>
            <button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
        </div>
    </header>
    <main>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
    
    document.querySelector('.mobile-menu-btn')?.addEventListener('click', function() {
        document.querySelector('nav')?.classList.toggle('active');
        document.querySelector('.auth-buttons')?.classList.toggle('active');
    });
</script>