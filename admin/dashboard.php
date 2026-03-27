<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$progCount = $pdo->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
$interestCount = $pdo->query("SELECT COUNT(*) FROM InterestedStudents")->fetchColumn();
$staffCount = $pdo->query("SELECT COUNT(*) FROM Staff")->fetchColumn();
$moduleCount = $pdo->query("SELECT COUNT(*) FROM Modules")->fetchColumn();

include 'header.php';
?>
<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <p class="welcome-text">Welcome, Admin! Here's your overview.</p>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            <div class="stat-number"><?= $progCount ?></div>
            <div class="stat-label">Total Programmes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book"></i></div>
            <div class="stat-number"><?= $moduleCount ?></div>
            <div class="stat-label">Total Modules</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number"><?= $staffCount ?></div>
            <div class="stat-label">Staff Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-number"><?= $interestCount ?></div>
            <div class="stat-label">Interested Students</div>
        </div>
    </div>
    
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="programme_create.php" class="action-btn"><i class="fas fa-plus-circle"></i> Add Programme</a>
            <a href="module_create.php" class="action-btn"><i class="fas fa-plus-circle"></i> Add Module</a>
            <a href="staff_create.php" class="action-btn"><i class="fas fa-plus-circle"></i> Add Staff</a>
            <a href="students.php" class="action-btn"><i class="fas fa-download"></i> View Students</a>
            <a href="export.php" class="action-btn"><i class="fas fa-file-csv"></i> Export CSV</a>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 1rem 0;
}
.welcome-text {
    color: var(--gray);
    margin-bottom: 2rem;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}
.stat-icon {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--dark);
}
.stat-label {
    color: var(--gray);
    margin-top: 0.5rem;
}
.quick-actions h2 {
    margin-bottom: 1rem;
}
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--gradient);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .action-buttons {
        flex-direction: column;
    }
    .action-btn {
        justify-content: center;
    }
}
</style>
<?php include 'footer.php'; ?>