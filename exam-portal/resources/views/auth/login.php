<div class="card" style="max-width:500px;margin:60px auto;">
  <h2 style="font-weight:700;">Welcome to SpecExam</h2>
  <p style="color:var(--text-sub)">Secure online assessments at scale.</p>

  <?php if (!empty($error)): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" action="/login">
    <label>Email</label>
    <input type="email" name="email" required>

    <label style="margin-top:12px;">Password</label>
    <input type="password" name="password" required>

    <button style="margin-top:16px;">Login</button>
  </form>
</div>
