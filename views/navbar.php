<?php if (isset($_SESSION['login'])) : ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <!-- <a class="navbar-brand" href="#">Navbar</a> -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./index.php?pizza/index">Pizzen</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./index.php?ingredient/index">Zutaten</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./index.php?purchase/index">Alle Bestellungen</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./index.php?card/card">Warenkorb</a>
          </li>
        </ul>

        <!-- Ausloggen --> 
          <form action="./index.php" method="post" class="d-flex">
            <button type="submit" name='signout' class="btn btn-warning"> 
              <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout 
            </button>
          </form>
      </div>
    </div>
  </nav>
<?php endif ?>
