<!-- INFO: User register -->

<?php 
require_once '/opt/lampp/htdocs/oop/Helpers/Helper.php';
$csrf_token = Helper::generateCSRFToken() 
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
    <title>Registrieren</title>
    <meta name='' content=''>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
  </head>
  
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center mt-5">Registrierung</h2>
            <form action="/oop/index.php" method="POST">
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
              <!-- csrf_token einfügen -->
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
    </div>

    <script src="../js/alert.js"></script>
  </body>
</html>
