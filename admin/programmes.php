<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$hiddenFile = __DIR__ . '/../data/hidden_programmes.json';

if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $hidden = file_exists($hiddenFile) ? json_decode(file_get_contents($hiddenFile), true) : [];
    if (in_array($id, $hidden)) {
        $hidden = array_diff($hidden, [$id]);
        $msg = "Programme published.";
    } else {
        $hidden[] = $id;
        $msg = "Programme hidden.";
    }
    file_put_contents($hiddenFile, json_encode(array_values($hidden)));
    $_SESSION['msg'] = $msg;
    header('Location: programmes.php');
    exit;
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Programmes WHERE ProgrammeID = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Programme deleted.";
    header('Location: programmes.php');
    exit;
}

$hidden = file_exists($hiddenFile) ? json_decode(file_get_contents($hiddenFile), true) : [];
$stmt = $pdo->query("SELECT p.*, l.LevelName FROM Programmes p LEFT JOIN Levels l ON p.LevelID = l.LevelID ORDER BY p.ProgrammeName");
$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<div class="table-container">
    <div class="table-header">
        <h1>Manage Programmes</h1>
        <a href="programme_create.php" class="button"><i class="fas fa-plus"></i> Add New Programme</a>
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
                    <th>Level</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programmes as $p): ?>
                <tr>
                    <td><?= $p['ProgrammeID'] ?></td>
                    <td><?= htmlspecialchars($p['ProgrammeName']) ?></td>
                    <td><?= htmlspecialchars($p['LevelName']) ?></td>
                    <td>
                        <span class="status-badge <?= in_array($p['ProgrammeID'], $hidden) ? 'status-hidden' : 'status-published' ?>">
                            <?= in_array($p['ProgrammeID'], $hidden) ? 'Hidden' : 'Published' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="programme_edit.php?id=<?= $p['ProgrammeID'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="programmes.php?delete=<?= $p['ProgrammeID'] ?>" class="btn-delete" onclick="return confirm('Delete this programme?')"><i class="fas fa-trash"></i> Delete</a>
                        <a href="programmes.php?toggle=<?= $p['ProgrammeID'] ?>" class="btn-toggle">
                            <?= in_array($p['ProgrammeID'], $hidden) ? '<i class="fas fa-eye"></i> Publish' : '<i class="fas fa-eye-slash"></i> Unpublish' ?>
                        </a>
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
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-published {
    background: #e6f7e6;
    color: #2e7d32;
}
.status-hidden {
    background: #ffe6e6;
    color: #c62828;
}
.actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.btn-edit, .btn-delete, .btn-toggle {
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
.btn-toggle {
    background: #f5f5f5;
    color: #666;
}
.btn-toggle:hover {
    background: var(--primary);
    color: white;
}
@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .actions {
        flex-direction: column;
    }
    .btn-edit, .btn-delete, .btn-toggle {
        text-align: center;
    }
}
</style>
<?php include 'footer.php'; ?>