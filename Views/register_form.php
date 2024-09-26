<!-- INFO: User register -->

<?php 
require_once __DIR__ . '/../Helpers/Helper.php';
$csrf_token = Helper::generateCSRFToken() 
?>

<?php $pageTitle = 'Registrieren'; include('head.php'); ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h2 class="text-center mt-5">Registrierung</h2>
        <form action="../index.php" method="POST">
          <div class="form-group">
            <label for="username">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Passwort bestätigen</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>

          <!-- CSRF-Token -->
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

          <div class="form-group">
            <button type="submit" name="register" class="btn btn-primary btn-block mt-2">Registrieren</button>
          </div>

          <div class="form-group text-center">
            <a href="./login_form.php" class="btn btn-link">Bereits registriert? Login</a>
          </div>
        </form>
      </div>
    </div>

    <div class="row justify-content-center mt-4">
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
  </div>
<?php include('tail.php'); ?>
