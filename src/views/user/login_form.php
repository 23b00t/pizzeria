<?php

namespace app\views\user;

use app\helpers\Helper;

$csrf_token = Helper::generateCSRFToken();
?>

<?php $pageTitle = 'Login';
include __DIR__ . '/../head.php'; ?>
  <div class="container">
  <div class="row justify-content-center">
  <div class="col-md-4">
  <h2 class="text-center mt-5">Login</h2>
  <form action="./index.php" method="POST">
  <div class="form-group">
  <label for="email">E-Mail</label>
  <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="form-group">
  <label for="password">Passwort</label>
  <input type="password" class="form-control" id="password" name="password" required>
  </div>

  <!-- csrf_token einfügen -->
  <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

  <!-- Set area in hidden field -->
  <input type="hidden" name="area" value="user">

  <!-- Set action in hidden field -->
  <input type="hidden" name="action" value="login">

  <div class="form-group">
  <button type="submit" name="login" class="btn btn-primary btn-block mt-2">Login</button>
  </div>
  <div class="form-group text-center">
  <a href="index.php?area=user&action=new" class="btn btn-link">Registrieren</a>
  </div>
  </form>
  </div>
  </div>
  </div>
<?php include __DIR__ . '/../tail.php'; ?>
