<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$programme_id = isset($_GET['programme']) ? $_GET['programme'] : 0;

$programme_name = '';
if ($programme_id) {
    // Use PDO instead of mysqli
    $stmt = $pdo->prepare("SELECT ProgrammeName FROM Programmes WHERE ProgrammeID = ?");
    $stmt->execute([$programme_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $programme_name = $row['ProgrammeName'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $prog_id = (int)$_POST['programme_id'];
    
    if (empty($name) || empty($email)) {
        $_SESSION['msg'] = 'Please fill in all fields.';
        $_SESSION['msg_type'] = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = 'Please enter a valid email.';
        $_SESSION['msg_type'] = 'error';
    } else {
        // Check if already registered using PDO
        $checkStmt = $pdo->prepare("SELECT * FROM InterestedStudents WHERE ProgrammeID = ? AND Email = ?");
        $checkStmt->execute([$prog_id, $email]);
        
        if ($checkStmt->rowCount() > 0) {
            $_SESSION['msg'] = 'You have already registered interest in this programme.';
            $_SESSION['msg_type'] = 'error';
        } else {
            // Insert using PDO
            $insertStmt = $pdo->prepare("INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email) VALUES (?, ?, ?)");
            if ($insertStmt->execute([$prog_id, $name, $email])) {
                $_SESSION['msg'] = 'Thank you! Your interest has been registered.';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['msg'] = 'Sorry, something went wrong. Please try again.';
                $_SESSION['msg_type'] = 'error';
            }
        }
    }
    header("Location: programme.php?id=$prog_id");
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h1>Register Your Interest</h1>

    <div class="form-container">
        <?php if ($programme_name): ?>
            <p>You are registering interest in: <strong><?php echo htmlspecialchars($programme_name); ?></strong></p>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <input type="hidden" name="programme_id" value="<?php echo $programme_id; ?>">
            
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>