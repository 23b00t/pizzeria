<?php 
session_status() === PHP_SESSION_NONE && session_start();

// https://stackoverflow.com/questions/22965067/when-and-why-i-should-use-session-regenerate-id#22965580
if (!isset($_SESSION["login"])) {
    header("Location: ./index.php");
    exit();
} else {
    session_regenerate_id(true);
}
?>

<?php $pageTitle = 'Dashboard'; include('./Views/head.php'); ?>
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
            <h1><?php echo $user->getUsername(); ?></h1>
        <?php else: ?>
            <p>Benutzer nicht gefunden.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php include('./Views/tail.php'); ?>
