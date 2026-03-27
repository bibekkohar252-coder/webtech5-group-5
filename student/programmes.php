<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$level_filter = isset($_GET['level']) ? intval($_GET['level']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$hiddenFile = __DIR__ . '/../data/hidden_programmes.json';
$hidden = file_exists($hiddenFile) ? json_decode(file_get_contents($hiddenFile), true) : [];

// Build query
$sql = "SELECT p.*, l.LevelName, s.Name as LeaderName 
        FROM Programmes p
        LEFT JOIN Levels l ON p.LevelID = l.LevelID
        LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
        WHERE 1=1";

$params = [];

if (!empty($hidden)) {
    $placeholders = implode(',', array_fill(0, count($hidden), '?'));
    $sql .= " AND p.ProgrammeID NOT IN ($placeholders)";
    $params = array_merge($params, $hidden);
}

if ($level_filter) {
    $sql .= " AND p.LevelID = ?";
    $params[] = $level_filter;
}

if ($search != '') {
    $sql .= " AND (p.ProgrammeName LIKE ? OR p.Description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$sql .= " ORDER BY p.ProgrammeName";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get levels for filter
$levelsStmt = $pdo->query("SELECT * FROM Levels ORDER BY LevelID");
$levels = $levelsStmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>All Programmes</h1>
        <p>Discover the perfect programme for your career journey</p>
    </div>
</div>

<div class="container">
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="get" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-layer-group"></i> Level</label>
                <select name="level">
                    <option value="">All Levels</option>
                    <?php foreach ($levels as $l): ?>
                        <option value="<?= $l['LevelID'] ?>" <?= $level_filter == $l['LevelID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['LevelName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Keyword</label>
                <input type="text" name="search" placeholder="Search programmes..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <button type="submit" class="filter-btn"><i class="fas fa-filter"></i> Apply Filters</button>
            <?php if ($level_filter || $search): ?>
                <a href="programmes.php" class="clear-btn"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Results Count -->
    <div class="results-count">
        <p>Found <strong><?= count($programmes) ?></strong> programme(s)</p>
    </div>

    <!-- Programme Grid -->
    <?php if (count($programmes) === 0): ?>
        <div class="no-results">
            <i class="fas fa-search fa-3x"></i>
            <h3>No programmes found</h3>
            <p>Try adjusting your search or filter criteria</p>
            <a href="programmes.php" class="btn-outline">Clear Filters</a>
        </div>
    <?php else: ?>
        <div class="programme-grid">
            <?php 
            $images = [
                'https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=400&h=250&fit=crop',
                'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?w=400&h=250&fit=crop'
            ];
            $index = 0;
            foreach ($programmes as $p): 
                $image = $p['Image'] ?: $images[$index % count($images)];
                $index++;
            ?>
                <div class="programme-card" data-aos="fade-up" data-aos-delay="<?= min($index * 50, 300) ?>">
                    <div class="card-image" style="background-image: url('<?= htmlspecialchars($image) ?>');">
                        <div class="card-overlay"></div>
                    </div>
                    <div class="card-content">
                        <span class="badge"><?= htmlspecialchars($p['LevelName']) ?></span>
                        <h3><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                        <p><?= htmlspecialchars(substr($p['Description'], 0, 100)) ?>…</p>
                        <a href="programme.php?id=<?= $p['ProgrammeID'] ?>" class="card-link">
                            Learn more <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    border-left: none;
    padding-left: 0;
    color: white;
}

.page-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

/* Filter Section */
.filter-section {
    background: white;
    padding: 1.5rem 2rem;
    border-radius: 60px;
    margin: -2rem auto 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    z-index: 10;
    max-width: 900px;
}

.filter-form {
    display: flex;
    gap: 1rem;
    align-items: flex-end;
    flex-wrap: wrap;
    justify-content: center;
}

.filter-group {
    flex: 1;
    min-width: 180px;
}

.filter-group label {
    display: block;
    margin-bottom: 0.3rem;
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--gray);
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 40px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: white;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.filter-btn {
    background: var(--gradient);
    color: white;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 40px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.clear-btn {
    background: #f0f0f0;
    color: #666;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.clear-btn:hover {
    background: #e0e0e0;
}

.results-count {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--gray);
}

.results-count strong {
    color: var(--primary);
}

.no-results {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 24px;
    margin: 2rem 0;
}

.no-results i {
    color: var(--gray);
    margin-bottom: 1rem;
}

.no-results h3 {
    margin-bottom: 0.5rem;
}

.no-results p {
    color: var(--gray);
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .filter-section {
        border-radius: 30px;
        padding: 1.5rem;
        margin-top: -1rem;
    }
    
    .filter-form {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-btn, .clear-btn {
        width: 100%;
        justify-content: center;
    }
    
    .page-header h1 {
        font-size: 1.8rem;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>