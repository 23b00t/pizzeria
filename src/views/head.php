<?php 
namespace app\views;
/** @var app\views $pageTitle */ 
?>
<!-- INFO: HTML Head-Template  -->
<!-- https://stackoverflow.com/questions/13071784/html-templates-php -->

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
        <title><?= $pageTitle ?></title>
    <meta name='' content=''>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
  </head>
  <body>
  <?php require __DIR__ . '/navbar.php'; ?>
  <!-- INFO: Body content follows here -->
    
