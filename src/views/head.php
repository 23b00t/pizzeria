<?php

namespace app\views;

/**
 * @var app\views $pageTitle
 * @var app $msg (optional; if message is handed by controller)
 */
?>
<!-- INFO: HTML Head-Template  -->
<!-- https://stackoverflow.com/questions/13071784/html-templates-php -->

<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='x-ua-compatible' content='ie=edge'>
  <title>
    <?= $pageTitle ?>
  </title>
  <meta name='' content=''>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" 
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  <div class='custom-alert m-2' style='display: none;' role='alert'>
    <?= isset($msg) ? $msg : ''; ?>
  </div>
  <!-- INFO: Body content follows here -->
