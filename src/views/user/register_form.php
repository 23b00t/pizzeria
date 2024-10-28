<?php

namespace app\views\user;

use app\helpers\Helper;

$csrf_token = Helper::generateCSRFToken();
$pageTitle = 'Registrieren';
?>

<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="text-center mt-5">Registrierung</h2>
    <form action="./index.php" method="POST">
      <div class="form-group">
        <label for="email">E-Mail</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Passwort</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Passwort bestätigen</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>
      <div class="form-group">
        <label for="first_name">Vorname</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
      </div>
      <div class="form-group">
        <label for="last_name">Nachname</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
      </div>
      <div class="form-group">
        <label for="street">Straße</label>
        <input type="text" class="form-control" id="street" name="street" required>
      </div>
      <div class="form-group">
        <label for="str_no">Hausnummer</label>
        <input type="number" class="form-control" id="str_no" name="str_no" required>
      </div>
      <div class="form-group">
        <label for="zip">PLZ</label>
        <input type="number" class="form-control" id="zip" name="zip" required>
      </div>
      <div class="form-group">
        <label for="city">Stadt</label>
        <input type="text" class="form-control" id="city" name="city" required>
      </div>
      <!-- CSRF-Token -->
      <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

      <!-- Set area in hidden field -->
      <input type="hidden" name="area" value="user">

      <!-- Set action in hidden field -->
      <input type="hidden" name="action" value="create">

      <div class="form-group">
        <button type="submit" name="register" class="btn btn-primary btn-block mt-2">Registrieren</button>
      </div>

      <div class="form-group text-center">
        <a href="./index.php" class="btn btn-link">Bereits registriert? Login</a>
      </div>
    </form>
  </div>
</div>

<div class="row justify-content-center mt-4 mb-4">
  <div class="col-md-6">
    <div class="alert alert-info" role="alert">
      <strong>Passwortanforderungen:</strong>
      <ul>
        <li>Mindestlänge: 8 Zeichen.</li>
        <li>Mindestens ein Großbuchstabe.</li>
        <li>Mindestens ein Kleinbuchstabe.</li>
        <li>Mindestens eine Ziffer.</li>
        <li>Mindestens ein Sonderzeichen.</li>
      </ul>
    </div>
  </div>
</div>
