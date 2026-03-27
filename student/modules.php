<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h1>All Modules</h1>

    <?php
    // Use PDO
    $stmt = $pdo->query("SELECT m.*, s.Name as ModuleLeaderName 
                         FROM Modules m 
                         LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID 
                         ORDER BY m.ModuleName");
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($modules) == 0): 
    ?>
        <div class="no-results">
            <p>No modules found.</p>
        </div>
    <?php else: ?>
        <div class="modules-list">
            <?php foreach ($modules as $module): 
                $progStmt = $pdo->prepare("SELECT DISTINCT p.ProgrammeID, p.ProgrammeName, l.LevelName
                                          FROM Programmes p
                                          JOIN ProgrammeModules pm ON p.ProgrammeID = pm.ProgrammeID
                                          JOIN Levels l ON p.LevelID = l.LevelID
                                          WHERE pm.ModuleID = ?
                                          ORDER BY p.ProgrammeName");
                $progStmt->execute([$module['ModuleID']]);
                $programmes = $progStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <div class="module-item">
                    <h2><?php echo htmlspecialchars($module['ModuleName']); ?></h2>
                    
                    <?php if ($module['ModuleLeaderName']): ?>
                        <p class="module-leader">
                            <strong>Module Leader:</strong> <?php echo htmlspecialchars($module['ModuleLeaderName']); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($module['Description']): ?>
                        <div class="module-description">
                            <p><?php echo nl2br(htmlspecialchars($module['Description'])); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="module-programmes">
                        <h3>Part of these programmes:</h3>
                        <?php if (count($programmes) > 0): ?>
                            <ul>
                                <?php foreach ($programmes as $prog): ?>
                                    <li>
                                        <a href="programme.php?id=<?php echo $prog['ProgrammeID']; ?>">
                                            <?php echo htmlspecialchars($prog['ProgrammeName']); ?> 
                                            <span class="level-badge">(<?php echo htmlspecialchars($prog['LevelName']); ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="no-programmes">Not currently used in any programme</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>