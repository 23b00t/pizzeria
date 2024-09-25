<?php 
session_status() === PHP_SESSION_NONE && session_start();

if (!isset($_SESSION["login"])) {
    header("Location: /opt/lampp/htdocs/oop/index.php");
    exit();
} else {
    session_regenerate_id(true);
}

?>
<!-- Ausloggen --> 
<div class="container mt-2 d-flex justify-content-end">
  <form action="/oop/index.php" method="post">
    <button type="submit" name='signout' class=" btn btn-warning mb-3"> 
      <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout 
    </button>
  </form>
</div>

<?php if ($user) : ?>
    <h1><?php echo $user->getUsername(); ?></h1>
<?php else: ?>
    <p>Benutzer nicht gefunden.</p>
<?php endif; ?>
