<?php

namespace app\views\purchase;

use app\helpers\Helper;
use app\models\User;

Helper::validateSession();
/** @var app\controllers\PurchaseController $purchases */
$pageTitle = 'Pizzen';
?>

<h1 class="mb-4">Bestellungen-Liste</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Bestellt am</th>
      <th>Ausgeliefert am</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($purchases as $purchase) : ?>
    <tr>
      <td><a href="./index.php?area=card&action=show&id=<?= htmlspecialchars($purchase->id()) ?>">
          <?= htmlspecialchars($purchase->id()) ?>
        </a></td>
      <td>
          <?= htmlspecialchars($purchase->purchased_at()) ?? 'offen' ?>
      </td>
      <td>
          <?= htmlspecialchars($purchase->delivered_at()) ?? 'offen' ?>
      </td>
      <td>
          <?= htmlspecialchars($purchase->status()) ?>
      </td>
      <td>
          <?php if (User::isAdmin()) : ?>
        <a href="./index.php?area=card&action=edit&id=<?= htmlspecialchars($purchase->id()) ?>"
          class="btn btn-warning btn-sm">Bearbeiten</a>
        <a href="./index.php?area=purchase&action=delete&id=<?= htmlspecialchars($purchase->id()) ?>"
          class="btn btn-danger btn-sm">Löschen</a>
        <a href="./index.php?area=purchase&action=update&id=<?= htmlspecialchars($purchase->id()) ?>"
          class="btn btn-success btn-sm">Geliefert</a>
          <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
