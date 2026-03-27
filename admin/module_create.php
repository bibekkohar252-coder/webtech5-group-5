<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /course_hub/admin/');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $leader = $_POST['leader'];
    $desc = $_POST['description'];
    $image = $_POST['image'];
    $stmt = $pdo->prepare("INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $leader, $desc, $image]);
    header('Location: modules.php');
    exit;
}

$staff = $pdo->query("SELECT * FROM Staff ORDER BY Name")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<div class="form-container">
    <h1><i class="fas fa-plus-circle"></i> Add New Module</h1>
    <form method="post" class="admin-form">
        <div class="form-group">
            <label for="name"><i class="fas fa-tag"></i> Module Name</label>
            <input type="text" name="name" id="name" required placeholder="e.g., Introduction to Programming">
        </div>
        
        <div class="form-group">
            <label for="leader"><i class="fas fa-user-tie"></i> Module Leader</label>
            <select name="leader" id="leader">
                <option value="">-- Select Module Leader --</option>
                <?php foreach ($staff as $s): ?>
                    <option value="<?= $s['StaffID'] ?>"><?= htmlspecialchars($s['Name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="description"><i class="fas fa-align-left"></i> Description</label>
            <textarea name="description" id="description" rows="5" placeholder="Enter module description..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="image"><i class="fas fa-image"></i> Image URL</label>
            <input type="text" name="image" id="image" placeholder="https://example.com/image.jpg">
        </div>
        
        <div class="form-buttons">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Module</button>
            <a href="modules.php" class="btn-cancel"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>

<style>
.form-container {
    max-width: 700px;
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
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-family: inherit;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
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