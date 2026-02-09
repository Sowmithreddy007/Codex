<div class="card">
  <h2 style="font-weight:700;">Create Exam + CSV Upload</h2>
  <?php if (!empty($error)): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" action="/admin/exam/create" enctype="multipart/form-data">
    <label>Title</label>
    <input name="title" required>

    <label style="margin-top:12px;">Duration (minutes)</label>
    <input name="duration" type="number" min="1" value="60" required>

    <div class="grid" style="margin-top:12px;">
      <div>
        <label>Start Time</label>
        <input name="start_time" type="datetime-local" required>
      </div>
      <div>
        <label>End Time</label>
        <input name="end_time" type="datetime-local" required>
      </div>
    </div>

    <label style="margin-top:12px;">Target Batch</label>
    <select name="target_batch_id" required>
      <?php foreach ($batches as $batch): ?>
        <option value="<?= htmlspecialchars($batch['id']) ?>"><?= htmlspecialchars($batch['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label style="margin-top:12px;">Questions CSV</label>
    <input type="file" name="questions_csv" accept=".csv" required>

    <button style="margin-top:16px;">Create Exam</button>
  </form>
</div>
