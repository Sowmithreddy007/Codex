<div class="card" style="display:flex;justify-content:space-between;align-items:center;">
  <h2 style="font-weight:700; margin:0;"><?= htmlspecialchars($exam['title'] ?? 'Exam') ?></h2>
  <div class="timer" id="timer" data-duration="<?= (int) ($exam['duration'] ?? 60) ?>">--:--</div>
</div>

<form class="card" id="exam-form" method="post" action="/student/exam/submit" data-exam-id="<?= (int) ($exam['id'] ?? 0) ?>">
  <input type="hidden" name="exam_id" value="<?= (int) ($exam['id'] ?? 0) ?>">

  <?php foreach ($questions as $index => $q): ?>
    <div class="question">
      <h4 style="font-weight:700;"><?= $index + 1 ?>. <?= htmlspecialchars($q['text']) ?></h4>
      <div class="options">
        <label><input type="radio" name="q_<?= (int) $q['id'] ?>" value="A"> A. <?= htmlspecialchars($q['option_a']) ?></label>
        <label><input type="radio" name="q_<?= (int) $q['id'] ?>" value="B"> B. <?= htmlspecialchars($q['option_b']) ?></label>
        <label><input type="radio" name="q_<?= (int) $q['id'] ?>" value="C"> C. <?= htmlspecialchars($q['option_c']) ?></label>
        <label><input type="radio" name="q_<?= (int) $q['id'] ?>" value="D"> D. <?= htmlspecialchars($q['option_d']) ?></label>
      </div>
    </div>
  <?php endforeach; ?>

  <button type="submit">Submit Exam</button>
</form>

<script src="/assets/js/exam-engine.js"></script>
<script src="/assets/js/anti-cheat.js"></script>
