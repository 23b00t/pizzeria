<?php 
require_once __DIR__ . '/../../Helpers/Helper.php';

Helper::validateSession();
?>

<?php $pageTitle = 'Dashboard'; require './Views/head.php'; ?>
  <div class="container">
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
