<?php use App\Helpers\SecurityHelper; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= SecurityHelper::e($title ?? 'SpecExam') ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="header">
  <div class="nav">
    <a class="brand" href="/">SpecExam</a>
    <?php if (!empty($_SESSION['user'])): ?>
      <div>
        <span><?= SecurityHelper::e($_SESSION['user']['name']) ?></span>
        <a class="btn btn-secondary" style="margin-left:10px;padding:10px 14px;width:auto" href="/logout">Logout</a>
      </div>
    <?php endif; ?>
  </div>
</header>
<main class="container">
