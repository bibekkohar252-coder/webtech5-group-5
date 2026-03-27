<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header('Location: /course_hub/staff/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

$staffId = $_SESSION['staff_id'];

// Modules led
$modulesStmt = $pdo->prepare("
    SELECT m.*, s.Name as LeaderName 
    FROM Modules m 
    LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID 
    WHERE m.ModuleLeaderID = ?
");
$modulesStmt->execute([$staffId]);
$modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

// Programmes that include each module
$programmesByModule = [];
foreach ($modules as $module) {
    $progStmt = $pdo->prepare("
        SELECT p.ProgrammeID, p.ProgrammeName, p.Description, p.Image, l.LevelName 
        FROM ProgrammeModules pm 
        JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID 
        LEFT JOIN Levels l ON p.LevelID = l.LevelID 
        WHERE pm.ModuleID = ?
    ");
    $progStmt->execute([$module['ModuleID']]);
    $programmesByModule[$module['ModuleID']] = $progStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Programmes led by this staff member
$programmesLedStmt = $pdo->prepare("
    SELECT p.*, l.LevelName 
    FROM Programmes p 
    LEFT JOIN Levels l ON p.LevelID = l.LevelID 
    WHERE p.ProgrammeLeaderID = ?
");
$programmesLedStmt->execute([$staffId]);
$programmesLed = $programmesLedStmt->fetchAll(PDO::FETCH_ASSOC);

$interestedStudentsByProgramme = [];
foreach ($programmesLed as $prog) {
    $studentsStmt = $pdo->prepare("
        SELECT * FROM InterestedStudents 
        WHERE ProgrammeID = ? 
        ORDER BY RegisteredAt DESC
    ");
    $studentsStmt->execute([$prog['ProgrammeID']]);
    $interestedStudentsByProgramme[$prog['ProgrammeID']] = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'header.php';
?>

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Staff Dashboard</h1>
        <p class="welcome-text">Welcome back, <?= htmlspecialchars($_SESSION['staff_name']) ?>! Here's your teaching overview.</p>
    </div>

    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book"></i></div>
            <div class="stat-number"><?= count($modules) ?></div>
            <div class="stat-label">Modules I Lead</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-number"><?= count($programmesLed) ?></div>
            <div class="stat-label">Programmes I Lead</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">
                <?php 
                $totalStudents = 0;
                foreach ($interestedStudentsByProgramme as $students) {
                    $totalStudents += count($students);
                }
                echo $totalStudents;
                ?>
            </div>
            <div class="stat-label">Interested Students</div>
        </div>
    </div>

    <!-- Modules I Lead Section -->
    <div class="section-card">
        <div class="section-header">
            <h2><i class="fas fa-book"></i> Modules I Lead</h2>
        </div>
        <?php if (count($modules) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>You are not currently leading any modules.</p>
            </div>
        <?php else: ?>
            <div class="modules-grid">
                <?php foreach ($modules as $module): ?>
                    <div class="module-card">
                        <div class="module-header">
                            <h3><?= htmlspecialchars($module['ModuleName']) ?></h3>
                            <span class="module-badge">Module Leader</span>
                        </div>
                        <div class="module-description">
                            <p><?= nl2br(htmlspecialchars($module['Description'] ?: 'No description available.')) ?></p>
                        </div>
                        <div class="module-prog-section">
                            <h4><i class="fas fa-link"></i> Programmes that include this module:</h4>
                            <?php if (count($programmesByModule[$module['ModuleID']]) === 0): ?>
                                <p class="no-data">This module is not part of any programme.</p>
                            <?php else: ?>
                                <div class="programme-tags">
                                    <?php foreach ($programmesByModule[$module['ModuleID']] as $prog): ?>
                                        <a href="/course_hub/student/programme.php?id=<?= $prog['ProgrammeID'] ?>" class="programme-tag">
                                            <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($prog['ProgrammeName']) ?>
                                            <span class="level-badge">(<?= htmlspecialchars($prog['LevelName']) ?>)</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Programmes I Lead Section -->
    <div class="section-card">
        <div class="section-header">
            <h2><i class="fas fa-graduation-cap"></i> Programmes I Lead</h2>
        </div>
        <?php if (count($programmesLed) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>You are not currently leading any programmes.</p>
            </div>
        <?php else: ?>
            <div class="programmes-list">
                <?php foreach ($programmesLed as $prog): ?>
                    <div class="programme-lead-card">
                        <div class="programme-header">
                            <h3>
                                <a href="/course_hub/student/programme.php?id=<?= $prog['ProgrammeID'] ?>">
                                    <?= htmlspecialchars($prog['ProgrammeName']) ?>
                                </a>
                            </h3>
                            <span class="level-badge"><?= htmlspecialchars($prog['LevelName']) ?></span>
                        </div>
                        <div class="programme-description">
                            <p><?= nl2br(htmlspecialchars($prog['Description'] ?: 'No description available.')) ?></p>
                        </div>
                        <div class="students-section">
                            <h4><i class="fas fa-users"></i> Interested Students (<?= count($interestedStudentsByProgramme[$prog['ProgrammeID']] ?? []) ?>)</h4>
                            <?php $students = $interestedStudentsByProgramme[$prog['ProgrammeID']] ?? []; ?>
                            <?php if (count($students) === 0): ?>
                                <p class="no-data">No students have registered interest yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="students-table">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-user"></i> Name</th>
                                                <th><i class="fas fa-envelope"></i> Email</th>
                                                <th><i class="fas fa-calendar"></i> Registered</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $s): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($s['StudentName']) ?></td>
                                                    <td><?= htmlspecialchars($s['Email']) ?></td>
                                                    <td><?= date('d M Y, H:i', strtotime($s['RegisteredAt'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-container {
    padding: 1rem 0;
}
.welcome-section {
    margin-bottom: 2rem;
}
.welcome-text {
    color: var(--gray);
    margin-top: 0.5rem;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
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
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
}
.stat-label {
    color: var(--gray);
    margin-top: 0.5rem;
}
.section-card {
    background: white;
    border-radius: 24px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid rgba(0, 0, 0, 0.05);
}
.section-header h2 {
    margin-bottom: 1rem;
    color: var(--primary);
    font-size: 1.3rem;
}
.section-header h2 i {
    margin-right: 0.5rem;
}
.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}
.module-card {
    background: var(--gray-light);
    border-radius: 16px;
    padding: 1.2rem;
    transition: all 0.3s ease;
}
.module-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-sm);
}
.module-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.module-header h3 {
    color: var(--primary);
    font-size: 1.1rem;
}
.module-badge {
    background: var(--gradient);
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.module-description {
    color: var(--gray);
    font-size: 0.85rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}
.module-prog-section h4 {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    color: var(--dark);
}
.programme-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.programme-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    text-decoration: none;
    color: var(--dark);
    transition: all 0.2s;
}
.programme-tag:hover {
    background: var(--primary);
    color: white;
}
.level-badge {
    font-size: 0.65rem;
    color: var(--gray);
}
.programme-lead-card {
    background: var(--gray-light);
    border-radius: 16px;
    padding: 1.2rem;
    margin-bottom: 1rem;
}
.programme-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}
.programme-header h3 a {
    color: var(--primary);
    text-decoration: none;
}
.programme-header h3 a:hover {
    text-decoration: underline;
}
.programme-description {
    color: var(--gray);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.students-section h4 {
    margin-bottom: 0.8rem;
    color: var(--dark);
}
.table-responsive {
    overflow-x: auto;
}
.students-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}
.students-table th,
.students-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #eef2f5;
}
.students-table th {
    background: var(--gray-light);
    font-weight: 600;
}
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--gray);
}
.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}
.no-data {
    color: var(--gray);
    font-style: italic;
    font-size: 0.85rem;
}
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .modules-grid {
        grid-template-columns: 1fr;
    }
    .programme-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
<?php include 'footer.php'; ?>