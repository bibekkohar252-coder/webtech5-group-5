<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
include 'header.php';
?>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-lock"></i>
            <h1>Admin Login</h1>
            <p>Access the administration panel</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" id="username" required placeholder="Enter username">
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-key"></i> Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter password">
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <div class="login-footer">
            <a href="/course_hub/student/index.php"><i class="fas fa-home"></i> Back to Student Home</a>
        </div>
    </div>
</div>

<style>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
}
.login-card {
    background: white;
    border-radius: 24px;
    padding: 2.5rem;
    max-width: 450px;
    width: 100%;
    box-shadow: var(--shadow-lg);
    text-align: center;
}
.login-header i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
}
.login-header h1 {
    border-left: none;
    padding-left: 0;
    margin-bottom: 0.5rem;
}
.login-header p {
    color: var(--gray);
    margin-bottom: 1.5rem;
}
.login-form .form-group {
    margin-bottom: 1.2rem;
    text-align: left;
}
.login-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}
.login-form input {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
}
.login-form input:focus {
    outline: none;
    border-color: var(--primary);
}
.btn-login {
    width: 100%;
    background: var(--gradient);
    color: white;
    padding: 0.8rem;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}
.login-footer {
    margin-top: 1.5rem;
}
.login-footer a {
    color: var(--gray);
    text-decoration: none;
}
.login-footer a:hover {
    color: var(--primary);
}
</style>
<?php include 'footer.php'; ?>