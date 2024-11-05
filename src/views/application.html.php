<?php

namespace app\views;

/**
 * @var app\views $pageTitle
 * @var app $msg (optional; if message is handed by controller)
 * @var $view
 */
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
    <title>Pizzeria</title>
    <meta name='' content=''>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" 
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>

  <body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class='custom-alert' style='display: none;' role='alert'>
      <?= isset($msg) ? $msg : ''; ?>
    </div>
    <div class="container mt-2">
      <?php include __DIR__ . '/' . $view . '.php'; ?>
    </div>
    <script src="/../pizzeria/src/js/alert.js"></script>
  </body>
</html>
