<?php

namespace app\views\ingredient;

use app\helpers\Helper;
use app\models\User;

Helper::validateSession();

// @var app\controllers\IngredientController $ingredients
?>

<?php $pageTitle = 'Zutaten';
require __DIR__ . '/../head.php'; ?>
  <div class="container mt-5">
  <h1 class="mb-4">Zutaten-Liste</h1>
  <table class="table table-striped">
  <thead>
  <tr>
  <th>ID</th>
  <th>Name</th>
  <th>Preis</th>
  <th>Vegetarisch</th>
  <?php if (User::isAdmin()) : ?>
  <th>Aktionen</th>
  <?php endif; ?>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($ingredients as $ingredient) : ?>
  <tr>
  <td><?= htmlspecialchars($ingredient->id()) ?></td>
  <td><?= htmlspecialchars($ingredient->name()) ?></td>
  <td><?= htmlspecialchars($ingredient->price()) ?> €</td>
  <td><?= htmlspecialchars($ingredient->vegetarian() == 1) ? 'X' : ''; ?> </td>
  <?php if (User::isAdmin()) : ?>
  <td>
  <a href="./index.php?area=ingredient&action=edit&id=<?= htmlspecialchars($ingredient->id()) ?>" 
  class="btn btn-warning btn-sm">Bearbeiten</a>
  <a href="./index.php?area=ingredient&action=delete&id=<?= htmlspecialchars($ingredient->id()) ?>"
  class="btn btn-danger btn-sm">Löschen</a>
  </td>
  <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <?php if (User::isAdmin()) : ?>
  <a href="./index.php?area=ingredient&action=create" class="btn btn-primary">Neue Zutaten hinzufügen</a>
  <?php endif; ?>
  </div>
<?php require __DIR__ . '/../tail.php'; ?>
