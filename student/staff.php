<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h1>Our Academic Staff</h1>

    <?php
    // Use PDO
    $stmt = $pdo->query("SELECT * FROM Staff ORDER BY Name");
    $staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="staff-grid">
        <?php foreach ($staffList as $staff): 
            $modulesStmt = $pdo->prepare("SELECT ModuleName FROM Modules WHERE ModuleLeaderID = ? ORDER BY ModuleName");
            $modulesStmt->execute([$staff['StaffID']]);
            $modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <div class="staff-card">
                <h2><?php echo htmlspecialchars($staff['Name']); ?></h2>
                
                <?php if (count($modules) > 0): ?>
                    <div class="staff-modules">
                        <h3>Module Leader for:</h3>
                        <ul>
                            <?php foreach ($modules as $module): ?>
                                <li><?php echo htmlspecialchars($module['ModuleName']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <p class="no-modules">Not currently leading any modules</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>