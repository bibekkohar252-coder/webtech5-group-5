<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? 0;
$staff = $pdo->prepare("SELECT * FROM Staff WHERE StaffID = ?");
$staff->execute([$id]);
$staff = $staff->fetch(PDO::FETCH_ASSOC);
if (!$staff) die("Staff not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("UPDATE Staff SET Name=? WHERE StaffID=?");
    $stmt->execute([$name, $id]);
    header('Location: staff.php');
    exit;
}

include 'header.php';
?>
<div class="form-container">
    <h1><i class="fas fa-edit"></i> Edit Staff Member</h1>
    <form method="post" class="admin-form">
        <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Staff Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($staff['Name']) ?>" required>
        </div>
        
        <div class="form-buttons">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Update Staff</button>
            <a href="staff.php" class="btn-cancel"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>

<style>
.form-container {
    max-width: 600px;
    margin: 0 auto;
}
.form-container h1 {
    margin-bottom: 1.5rem;
}
.admin-form {
    background: white;
    padding: 2rem;
    border-radius: 24px;
    box-shadow: var(--shadow-md);
}
.form-group {
    margin-bottom: 1.2rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}
.form-group input {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
}
.form-group input:focus {
    outline: none;
    border-color: var(--primary);
}
.form-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}
.btn-save {
    background: var(--gradient);
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}
.btn-cancel {
    background: #f0f0f0;
    color: #666;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
}
.btn-cancel:hover {
    background: #e0e0e0;
}
@media (max-width: 768px) {
    .form-buttons {
        flex-direction: column;
    }
}
</style>
<?php include 'footer.php'; ?>