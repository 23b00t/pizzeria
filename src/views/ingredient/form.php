<?php

namespace app\views\ingredient;

use app\helpers\Helper;

Helper::validateSession();
$csrf_token = Helper::generateCSRFToken();
/** @var controllers\IngredientController $ingredient */
?>

<?php
$pageTitle = isset($ingredient) ? 'Zutaten bearbeiten' : 'Neue Zutaten erstellen';
?>
<h1 class="mb-4">
  <?= isset($ingredient) ? 'Zutaten bearbeiten' : 'Neue Zutaten erstellen' ?>
</h1>

<form action="<?= isset($ingredient) ? './index.php?area=ingredient&action=update&id='
  . htmlspecialchars($ingredient->id()) : './index.php?area=ingredient&action=store' ?>" method="POST">
  <div class="form-group mb-3">
    <label for="name">Zutaten Name</label>
    <input type="text" name="name" id="name" class="form-control"
      value="<?= isset($ingredient) ? htmlspecialchars($ingredient->name()) : '' ?>" required>
  </div>

  <div class="form-group mb-3">
    <label for="price">Preis (€)</label>
    <input type="number" step="0.01" name="price" id="price" class="form-control"
      value="<?= isset($ingredient) ? htmlspecialchars($ingredient->price()) : '' ?>" required>
  </div>

  <div class="form-group mb-3">
    <input type="checkbox" name="vegetarian" id="vegetarian" class="form-check-input" <?=(isset($ingredient) &&
      $ingredient->vegetarian()) ? 'checked' : '' ?>>
    <label class="form-check-label" for="vegetarian">Diese Zutat ist vegetarisch</label>
  </div>
  <!-- csrf_token einfügen -->
  <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

  <button type="submit" class="btn btn-primary">
    <?= isset($ingredient) ? 'Zutaten aktualisieren' : 'Zutaten erstellen' ?>
  </button>
  <a href="./index.php?area=ingredient&action=index" class="btn btn-secondary">Abbrechen</a>
</form>
