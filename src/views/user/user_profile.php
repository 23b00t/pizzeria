<?php

namespace app\views\user;

use app\helpers\Helper;

Helper::validateSession();
// @var app\controllers\UserController $user
$pageTitle = 'Dashboard';
?>

<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="text-center mt-5">Benutzer</h2>
    <?php if ($user) : ?>
    <h1>
          <?= $user->email(); ?>
    </h1>
    <?php else : ?>
    <p>Benutzer nicht gefunden.</p>
    <?php endif; ?>
  </div>
</div>
