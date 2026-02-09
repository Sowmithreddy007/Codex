(() => {
  const examForm = document.getElementById('exam-form');
  if (!examForm) return;

  const examId = examForm.dataset.examId;
  const timerNode = document.getElementById('timer');
  let seconds = Number(timerNode?.dataset.duration || 0) * 60;

  const saveProgress = () => {
    const formData = new FormData(examForm);
    const answers = {};
    for (const [key, val] of formData.entries()) {
      if (key.startsWith('q_')) {
        answers[key.replace('q_', '')] = val;
      }
    }

    const payload = new URLSearchParams();
    payload.append('exam_id', examId);
    Object.keys(answers).forEach(k => payload.append(`answers[${k}]`, answers[k]));

    fetch('/api/save-progress', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: payload.toString()
    });
  };

  setInterval(saveProgress, 30000);

  const tick = () => {
    seconds = Math.max(seconds - 1, 0);
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    if (timerNode) timerNode.textContent = `${m}:${s}`;
    if (seconds === 0) {
      saveProgress();
      examForm.submit();
    }
  };

  setInterval(tick, 1000);
})();
