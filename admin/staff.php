<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Staff WHERE StaffID = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Staff deleted.";
    header('Location: staff.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM Staff ORDER BY Name");
$staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<div class="table-container">
    <div class="table-header">
        <h1>Manage Staff</h1>
        <a href="staff_create.php" class="button"><i class="fas fa-plus"></i> Add New Staff</a>
    </div>
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffList as $s): ?>
                <tr>
                    <td><?= $s['StaffID'] ?></td>
                    <td><?= htmlspecialchars($s['Name']) ?></td>
                    <td class="actions">
                        <a href="staff_edit.php?id=<?= $s['StaffID'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="staff.php?delete=<?= $s['StaffID'] ?>" class="btn-delete" onclick="return confirm('Delete this staff member?')"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.table-responsive {
    overflow-x: auto;
}
.actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.btn-edit, .btn-delete {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: all 0.2s;
}
.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
}
.btn-edit:hover {
    background: #1976d2;
    color: white;
}
.btn-delete {
    background: #ffebee;
    color: #d32f2f;
}
.btn-delete:hover {
    background: #d32f2f;
    color: white;
}
@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
<?php include 'footer.php'; ?>