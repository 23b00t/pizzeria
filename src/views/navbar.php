<?php

namespace app\views;

use app\models\User;

?>

<?php if (isset($_SESSION['login'])) : ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <!-- <a class="navbar-brand" href="#">Navbar</a> -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav me-auto">
  <li class="nav-item">
  <a class="nav-link active" aria-current="page" href="./index.php?area=pizza&action=index">Pizzen</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="./index.php?area=ingredient&action=index">Zutaten</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="./index.php?area=purchase&action=index">Alle Bestellungen</a>
  </li>
  <?php if (!User::isAdmin()) : ?>
  <li class="nav-item">
  <a class="nav-link" href="./index.php?area=card&action=showOpenCard">Warenkorb</a>
  </li>
  <?php endif; ?>
  </ul>

  <!-- Ausloggen --> 
  <a href="./index.php?area=user&action=signOut">
  <button type="submit" name='signout' class="btn btn-warning"> 
  <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout 
  </button>
  </a>
  </div>
  </div>
  </nav>
<?php endif ?>
