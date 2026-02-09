(() => {
  const examForm = document.getElementById('exam-form');
  if (!examForm) return;

  let tabSwitches = 0;

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      tabSwitches += 1;
      if (tabSwitches > 3) {
        alert('Too many tab switches. Exam will be auto-submitted.');
        examForm.submit();
      }
    }
  });

  document.addEventListener('contextmenu', e => e.preventDefault());
  document.addEventListener('copy', e => e.preventDefault());
  document.addEventListener('paste', e => e.preventDefault());
})();
