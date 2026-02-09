<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\Question;
use Throwable;

final class ExamController
{
    public function createForm(): void
    {
        $batches = (new Batch())->all();
        view('admin/create_exam', ['title' => 'Create Exam', 'batches' => $batches]);
    }

    public function create(): void
    {
        $examModel = new Exam();
        $questionModel = new Question();

        $examId = $examModel->create([
            'title' => trim($_POST['title'] ?? ''),
            'duration' => (int) ($_POST['duration'] ?? 60),
            'start_time' => $_POST['start_time'] ?? date('Y-m-d H:i:s'),
            'end_time' => $_POST['end_time'] ?? date('Y-m-d H:i:s', strtotime('+1 day')),
            'created_by' => (int) $_SESSION['user']['id'],
        ]);

        $batchId = $_POST['target_batch_id'] ?? 'E1';
        $examModel->assignToBatch($examId, $batchId);

        $file = $_FILES['questions_csv'] ?? null;
        if (!$file || !is_uploaded_file($file['tmp_name'])) {
            view('admin/create_exam', ['title' => 'Create Exam', 'error' => 'CSV file is required.', 'batches' => (new Batch())->all()]);
            return;
        }

        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            view('admin/create_exam', ['title' => 'Create Exam', 'error' => 'Cannot read CSV file.', 'batches' => (new Batch())->all()]);
            return;
        }

        $rows = [];
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = [
                'text' => $data[0] ?? '',
                'option_a' => $data[1] ?? '',
                'option_b' => $data[2] ?? '',
                'option_c' => $data[3] ?? '',
                'option_d' => $data[4] ?? '',
                'correct_option' => strtoupper($data[5] ?? 'A'),
                'topic_tag' => $data[6] ?? 'general',
            ];
        }
        fclose($handle);

        $pdo = Database::instance()->pdo();
        try {
            $pdo->beginTransaction();
            $questionModel->bulkInsert($examId, $rows);
            $pdo->commit();
        } catch (Throwable $throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            view('admin/create_exam', ['title' => 'Create Exam', 'error' => 'Upload failed. Transaction rolled back.', 'batches' => (new Batch())->all()]);
            return;
        }

        header('Location: /admin/dashboard');
        exit;
    }
}
