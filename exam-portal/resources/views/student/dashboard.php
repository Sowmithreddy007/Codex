<div class="card">
  <h2 style="font-weight:700;">Student Dashboard</h2>
  <p style="color:var(--text-sub)">Assigned exams for your batch.</p>
</div>

<div class="grid">
<?php foreach ($exams as $exam): ?>
  <div class="card">
    <h3 style="font-weight:700; margin-top:0;"><?= htmlspecialchars($exam['title']) ?></h3>
    <p>Duration: <?= (int) $exam['duration'] ?> min</p>
    <a class="btn" style="padding:12px 16px;" href="/student/exam/<?= (int) $exam['id'] ?>">Start Exam</a>
  </div>
<?php endforeach; ?>
</div>
