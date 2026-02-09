<?php

declare(strict_types=1);

namespace App\Config;

final class Constants
{
    public const APP_NAME = 'SpecExam';
    public const SESSION_NAME = 'specexam_session';

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STUDENT = 'student';

    public const REDIS_EXAM_QUESTIONS_KEY = 'exam_%d_questions';
    public const REDIS_USER_TEMP_ANSWERS_KEY = 'user_%d_temp_answers';

    public const CACHE_TTL_EXAM_QUESTIONS = 3600;
    public const CACHE_TTL_TEMP_ANSWERS = 7200;
}
