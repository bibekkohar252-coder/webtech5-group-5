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
    $stmt = $pdo->prepare("DELETE FROM Modules WHERE ModuleID = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Module deleted.";
    header('Location: modules.php');
    exit;
}

$stmt = $pdo->query("SELECT m.*, s.Name as LeaderName FROM Modules m LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID ORDER BY m.ModuleName");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<div class="table-container">
    <div class="table-header">
        <h1>Manage Modules</h1>
        <a href="module_create.php" class="button"><i class="fas fa-plus"></i> Add New Module</a>
    </div>
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Module Name</th>
                    <th>Module Leader</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $m): ?>
                <tr>
                    <td><?= $m['ModuleID'] ?></td>
                    <td><?= htmlspecialchars($m['ModuleName']) ?></td>
                    <td><?= htmlspecialchars($m['LeaderName'] ?: 'Not Assigned') ?></td>
                    <td class="actions">
                        <a href="module_edit.php?id=<?= $m['ModuleID'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="modules.php?delete=<?= $m['ModuleID'] ?>" class="btn-delete" onclick="return confirm('Delete this module?')"><i class="fas fa-trash"></i> Delete</a>
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