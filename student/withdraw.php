<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /course_hub/student/');
    exit;
}

$programme_id = $_POST['programme_id'] ?? 0;
$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg'] = 'Please provide a valid email.';
    $_SESSION['msg_type'] = 'error';
    header("Location: programme.php?id=$programme_id");
    exit;
}

// Use PDO for delete
$stmt = $pdo->prepare("DELETE FROM InterestedStudents WHERE ProgrammeID = ? AND Email = ?");
if ($stmt->execute([$programme_id, $email])) {
    if ($stmt->rowCount() > 0) {
        $_SESSION['msg'] = 'Your interest has been withdrawn.';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['msg'] = 'No registration found with that email.';
        $_SESSION['msg_type'] = 'error';
    }
} else {
    $_SESSION['msg'] = 'Withdrawal failed.';
    $_SESSION['msg_type'] = 'error';
}
header("Location: programme.php?id=$programme_id");
exit;