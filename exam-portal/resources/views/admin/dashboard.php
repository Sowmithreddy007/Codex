<div class="card">
  <h2 style="font-weight:700;">Admin Dashboard</h2>
  <a class="btn" style="padding:12px 16px;width:auto" href="/admin/exam/create">Create New Exam</a>
</div>

<div class="card">
  <h3 style="font-weight:700;">All Exams</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Title</th><th>Duration</th><th>Start</th><th>End</th></tr></thead>
    <tbody>
    <?php foreach ($exams as $exam): ?>
      <tr>
        <td><?= (int) $exam['id'] ?></td>
        <td><?= htmlspecialchars($exam['title']) ?></td>
        <td><?= (int) $exam['duration'] ?> min</td>
        <td><?= htmlspecialchars($exam['start_time']) ?></td>
        <td><?= htmlspecialchars($exam['end_time']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
