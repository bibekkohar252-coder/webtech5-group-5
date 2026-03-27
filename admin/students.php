<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->query("SELECT i.*, p.ProgrammeName FROM InterestedStudents i JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID ORDER BY i.RegisteredAt DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<div class="table-container">
    <div class="table-header">
        <h1>Student Interests</h1>
        <a href="export.php" class="button"><i class="fas fa-download"></i> Export CSV</a>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Programme</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= $s['InterestID'] ?></td>
                    <td><?= htmlspecialchars($s['ProgrammeName']) ?></td>
                    <td><?= htmlspecialchars($s['StudentName']) ?></td>
                    <td><?= htmlspecialchars($s['Email']) ?></td>
                    <td><?= date('d M Y, H:i', strtotime($s['RegisteredAt'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (count($students) == 0): ?>
        <div class="no-data">
            <i class="fas fa-inbox"></i>
            <p>No student interests registered yet.</p>
        </div>
    <?php endif; ?>
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
.no-data {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 20px;
    margin-top: 1rem;
}
.no-data i {
    font-size: 3rem;
    color: var(--gray);
    margin-bottom: 1rem;
}
.no-data p {
    color: var(--gray);
}
@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
<?php include 'footer.php'; ?>