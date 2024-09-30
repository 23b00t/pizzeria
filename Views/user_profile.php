<?php 
require_once __DIR__ . '/../Helpers/Helper.php';

Helper::validateSession();
?>

<?php $pageTitle = 'Dashboard'; require './Views/head.php'; ?>
  <div class="container">
    <!-- Ausloggen --> 
    <div class="container mt-2 d-flex justify-content-end">
      <form action="./index.php" method="post">
        <button type="submit" name='signout' class=" btn btn-warning mb-3"> 
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout 
        </button>
      </form>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h2 class="text-center mt-5">Benutzer</h2>
        <?php if ($user) : ?>
            <h1><?php echo $user->email(); ?></h1>
        <?php else: ?>
            <p>Benutzer nicht gefunden.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php require './Views/tail.php'; ?>
