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

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_interests_' . date('Y-m-d') . '.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Interest ID', 'Programme ID', 'Programme Name', 'Student Name', 'Email', 'Registered At']);
foreach ($students as $row) {
    fputcsv($output, [
        $row['InterestID'],
        $row['ProgrammeID'],
        $row['ProgrammeName'],
        $row['StudentName'],
        $row['Email'],
        $row['RegisteredAt']
    ]);
}
fclose($output);
exit;
?>