<div class="card">
  <h2 style="font-weight:700;">Exam Report #<?= (int) $examId ?></h2>
</div>

<?php if (!empty($report)): ?>
<div class="grid">
  <div class="stat">
    <p style="color:var(--text-sub)">Score</p>
    <h2 style="font-weight:700;"><?= number_format((float) $report['score'], 2) ?>%</h2>
  </div>
  <div class="stat">
    <p style="color:var(--text-sub)">Rank</p>
    <h2 style="font-weight:700;">#<?= (int) $report['rank'] ?></h2>
  </div>
  <div class="stat">
    <p style="color:var(--text-sub)">Batch Average</p>
    <h2 style="font-weight:700;"><?= number_format((float) $report['batch_avg'], 2) ?>%</h2>
  </div>
</div>
<?php else: ?>
<div class="card"><p>No report available yet.</p></div>
<?php endif; ?>
