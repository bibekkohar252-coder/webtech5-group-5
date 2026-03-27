<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$programme_id = isset($_GET['id']) ? $_GET['id'] : 0;

$hiddenFile = __DIR__ . '/../data/hidden_programmes.json';
$hidden = file_exists($hiddenFile) ? json_decode(file_get_contents($hiddenFile), true) : [];
if (in_array($programme_id, $hidden) && (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)) {
    die("Programme not found.");
}

// Use PDO instead of mysqli
$stmt = $pdo->prepare("SELECT p.*, l.LevelName, s.Name as LeaderName 
                       FROM Programmes p 
                       JOIN Levels l ON p.LevelID = l.LevelID 
                       LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID 
                       WHERE p.ProgrammeID = ?");
$stmt->execute([$programme_id]);
$programme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$programme) {
    echo "<p>Programme not found.</p>";
    include __DIR__ . '/includes/footer.php';
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<div class="programme-detail-container">
    <!-- Programme Header -->
    <div class="programme-header-detail">
        <div class="container">
            <div class="programme-title-section">
                <h1><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h1>
                <div class="programme-meta">
                    <span class="meta-badge"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($programme['LevelName']); ?></span>
                    <span class="meta-badge"><i class="fas fa-user-tie"></i> Programme Leader: <?php echo htmlspecialchars($programme['LeaderName'] ?: 'TBC'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Programme Image -->
        <?php if (!empty($programme['Image'])): ?>
            <div class="programme-image-section">
                <img src="images/<?php echo htmlspecialchars($programme['Image']); ?>" 
                     alt="<?php echo htmlspecialchars($programme['ProgrammeName']); ?>"
                     class="programme-detail-image">
            </div>
        <?php endif; ?>

        <!-- Programme Description -->
        <div class="programme-description-section">
            <h2><i class="fas fa-info-circle"></i> About this course</h2>
            <div class="description-content">
                <p><?php echo nl2br(htmlspecialchars($programme['Description'])); ?></p>
            </div>
        </div>

        <!-- Modules Section -->
        <div class="modules-section">
            <h2><i class="fas fa-book"></i> Modules by Year</h2>
            
            <?php
            // Use PDO for modules query
            $modulesStmt = $pdo->prepare("SELECT pm.Year, m.ModuleID, m.ModuleName, m.Description, s.Name as ModuleLeader 
                                          FROM ProgrammeModules pm 
                                          JOIN Modules m ON pm.ModuleID = m.ModuleID 
                                          LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID 
                                          WHERE pm.ProgrammeID = ? 
                                          ORDER BY pm.Year, m.ModuleName");
            $modulesStmt->execute([$programme_id]);
            $modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);
            $current_year = 0;
            
            if (count($modules) == 0):
            ?>
                <div class="no-modules">
                    <p>No modules found for this programme.</p>
                </div>
            <?php else: ?>
                <div class="modules-years">
                    <?php foreach ($modules as $module):
                        if ($module['Year'] != $current_year):
                            if ($current_year > 0) echo '</div>';
                            $current_year = $module['Year'];
                            echo '<div class="year-group">';
                            echo '<h3><i class="fas fa-calendar-alt"></i> Year ' . $current_year . '</h3>';
                            echo '<div class="modules-grid-detail">';
                        endif;
                    ?>
                        <div class="module-card-detail">
                            <div class="module-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <div class="module-info">
                                <h4><?php echo htmlspecialchars($module['ModuleName']); ?></h4>
                                <p class="module-leader-detail">
                                    <i class="fas fa-chalkboard-user"></i> 
                                    <strong>Module Leader:</strong> <?php echo htmlspecialchars($module['ModuleLeader'] ?: 'TBC'); ?>
                                </p>
                                <p class="module-description-detail">
                                    <?php echo htmlspecialchars($module['Description'] ?: 'No description available.'); ?>
                                </p>
                            </div>
                        </div>
                    <?php 
                    endforeach;
                    if ($current_year > 0) echo '</div></div>';
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Interest Section -->
        <div class="interest-section-detail">
            <div class="interest-content">
                <div class="interest-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h2>Interested in this course?</h2>
                <p>Register your interest to receive updates about open days, application deadlines, and programme news.</p>
                <a href="register.php?programme=<?php echo $programme_id; ?>" class="btn-interest">
                    <i class="fas fa-paper-plane"></i> Register Your Interest
                </a>
            </div>
        </div>

        <!-- Withdraw Section -->
        <div class="withdraw-section">
            <h2><i class="fas fa-trash-alt"></i> Withdraw Interest</h2>
            <p>Already registered? Withdraw your interest using the email you registered with.</p>
            <form action="withdraw.php" method="post" class="withdraw-form">
                <input type="hidden" name="programme_id" value="<?php echo $programme_id; ?>">
                <div class="form-row">
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                    <button type="submit" class="btn-withdraw">Withdraw Interest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Programme Detail Page Styles */
.programme-detail-container {
    padding-bottom: 3rem;
}

.programme-header-detail {
    background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
}

.programme-title-section h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    border-left: none;
    padding-left: 0;
    color: white;
}

.programme-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.5rem 1rem;
    border-radius: 40px;
    font-size: 0.9rem;
}

.programme-image-section {
    margin-bottom: 2rem;
}

.programme-detail-image {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 24px;
    box-shadow: var(--shadow-lg);
}

.programme-description-section {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.programme-description-section h2 {
    color: var(--primary);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.description-content p {
    color: var(--gray);
    line-height: 1.8;
    font-size: 1rem;
}

.modules-section {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.modules-section h2 {
    color: var(--primary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.year-group {
    margin-bottom: 2rem;
}

.year-group h3 {
    font-size: 1.2rem;
    color: var(--dark);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-light);
    display: inline-block;
}

.modules-grid-detail {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.module-card-detail {
    background: var(--gray-light);
    border-radius: 20px;
    padding: 1.2rem;
    display: flex;
    gap: 1rem;
    transition: all 0.3s ease;
}

.module-card-detail:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.module-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.module-info {
    flex: 1;
}

.module-info h4 {
    font-size: 1rem;
    margin-bottom: 0.3rem;
    color: var(--dark);
}

.module-leader-detail {
    font-size: 0.8rem;
    color: var(--gray);
    margin-bottom: 0.5rem;
}

.module-leader-detail i {
    color: var(--primary);
    margin-right: 0.3rem;
}

.module-description-detail {
    font-size: 0.85rem;
    color: var(--gray);
    line-height: 1.5;
}

.interest-section-detail {
    background: linear-gradient(135deg, #ff6b35 0%, #ffb347 100%);
    border-radius: 24px;
    padding: 3rem;
    text-align: center;
    margin-bottom: 2rem;
}

.interest-icon {
    font-size: 3rem;
    color: white;
    margin-bottom: 1rem;
}

.interest-section-detail h2 {
    color: white;
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

.interest-section-detail p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1.5rem;
}

.btn-interest {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    color: var(--primary);
    padding: 0.8rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-interest:hover {
    transform: translateY(-2px);
    gap: 0.8rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
}

.withdraw-section {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}

.withdraw-section h2 {
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.withdraw-section h2 i {
    color: #e53e3e;
}

.withdraw-section p {
    color: var(--gray);
    margin-bottom: 1rem;
}

.withdraw-form {
    background: none;
    padding: 0;
    margin: 0;
    box-shadow: none;
}

.form-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-row input {
    flex: 1;
    padding: 0.8rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
}

.form-row input:focus {
    outline: none;
    border-color: var(--primary);
}

.btn-withdraw {
    background: #e53e3e;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-withdraw:hover {
    background: #c53030;
    transform: translateY(-2px);
}

.no-modules {
    text-align: center;
    padding: 2rem;
    background: var(--gray-light);
    border-radius: 16px;
    color: var(--gray);
}

/* Alerts */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin: 1rem 0;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

@media (max-width: 768px) {
    .programme-title-section h1 {
        font-size: 1.8rem;
    }
    
    .modules-grid-detail {
        grid-template-columns: 1fr;
    }
    
    .module-card-detail {
        flex-direction: column;
        text-align: center;
    }
    
    .module-icon {
        margin: 0 auto;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .interest-section-detail {
        padding: 2rem;
    }
    
    .interest-section-detail h2 {
        font-size: 1.3rem;
    }
}
</style>

<?php
// Display session messages if any
if (isset($_SESSION['msg'])) {
    $alertClass = $_SESSION['msg_type'] === 'error' ? 'alert-error' : 'alert-success';
    echo '<div class="container"><div class="alert ' . $alertClass . '">' . htmlspecialchars($_SESSION['msg']) . '</div></div>';
    unset($_SESSION['msg'], $_SESSION['msg_type']);
}
?>

<?php include __DIR__ . '/includes/footer.php'; ?>