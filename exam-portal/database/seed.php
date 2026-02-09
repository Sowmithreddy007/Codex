<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=spec_exam;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$pdo->exec("INSERT INTO batches (id, name) VALUES ('E1','Engineering Year 1'), ('E2','Engineering Year 2') ON DUPLICATE KEY UPDATE name=VALUES(name)");

$adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
$studentPassword = password_hash('student123', PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash,role,batch_id) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name), password_hash=VALUES(password_hash), role=VALUES(role), batch_id=VALUES(batch_id)');
$stmt->execute(['Admin User', 'admin@specexam.test', $adminPassword, 'admin', 'E1']);

for ($i = 1; $i <= 5; $i++) {
    $batch = $i <= 3 ? 'E1' : 'E2';
    $stmt->execute(["Student {$i}", "student{$i}@specexam.test", $studentPassword, 'student', $batch]);
}

$adminId = (int) $pdo->query("SELECT id FROM users WHERE email='admin@specexam.test'")->fetchColumn();

$examStmt = $pdo->prepare('INSERT INTO exams (id,title,duration,start_time,end_time,created_by) VALUES (1,?,?,?,?,?) ON DUPLICATE KEY UPDATE title=VALUES(title), duration=VALUES(duration), start_time=VALUES(start_time), end_time=VALUES(end_time), created_by=VALUES(created_by)');
$examStmt->execute(['Sample Entrance Assessment', 60, date('Y-m-d H:i:s', strtotime('-1 hour')), date('Y-m-d H:i:s', strtotime('+12 hours')), $adminId]);

$pdo->exec("INSERT INTO exam_assignments (exam_id,target_batch_id) VALUES (1,'E1'),(1,'E2') ON DUPLICATE KEY UPDATE target_batch_id=VALUES(target_batch_id)");

$qStmt = $pdo->prepare('INSERT INTO questions (exam_id,text,option_a,option_b,option_c,option_d,correct_option,topic_tag) VALUES (?,?,?,?,?,?,?,?)');
$pdo->exec('DELETE FROM questions WHERE exam_id=1');
for ($q = 1; $q <= 10; $q++) {
    $qStmt->execute([1, "Question {$q}: Choose the correct option", 'Option A', 'Option B', 'Option C', 'Option D', 'A', 'sample']);
}

echo "Seed completed.\n";
