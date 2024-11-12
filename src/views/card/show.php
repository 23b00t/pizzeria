<?php

namespace app\views\card;

use app\models\User;
use app\helpers\Helper;
use app\models\Pizza;

Helper::validateSession();
$csrf_token = Helper::generateCSRFToken();

// @var controllers\PurchaseController $purchase
// @var controllers\CardController $cards
?>
<?php $pageTitle = 'Bestellung'; ?>
<?php if (!empty($cards)) : ?>
  <h1>Bestellung #
    <?= htmlspecialchars($purchase->id()); ?>
  </h1>
  <div class="purchase-details">
    <p><strong>Bestelldatum:</strong>
      <?= htmlspecialchars($purchase->purchased_at()); ?>
    </p>
    <p><strong>Status:</strong>
      <?= htmlspecialchars($purchase->status()); ?>
    </p>
  </div>

  <h2>Pizzen in der Bestellung:</h2>
  <ul>
    <?php foreach ($cards as $cardItem) : ?>
    <li>
        <?php if ($cardItem->pizza_id() !== null) : ?>
      <strong>
        <a href="./index.php?area=pizza&action=show&id=<?= htmlspecialchars($cardItem->pizza_id()) ?>">
            <?= htmlspecialchars(Pizza::findBy($cardItem->pizza_id(), 'id')->name()) ?>
        </a>
      </strong>
        <?php else : ?>
      <strong>Pizza nicht verfügbar</strong>
        <?php endif; ?>
        <?php if ($purchase->status() === 'pending') : ?>
      (Anzahl:
      <form action="./index.php?card/update" method="POST" style="display:inline;">
        <input type="number" name="quantity" value="<?= htmlspecialchars($cardItem->quantity()); ?>" min="1" required
          style="width: 60px;">
        <!-- insert csrf_token -->
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
        <!-- Add hidden field for card ID -->
        <input type="hidden" name="card_id" value="<?= htmlspecialchars($cardItem->id()); ?>">
        <input type="hidden" name="area" value="card">
        <input type="hidden" name="action" value="update">
        <button type="submit" class="btn btn-sm btn-primary">Ändern</button>
      </form>
      )
      <a href="./index.php?area=card&action=delete&id=<?= htmlspecialchars($cardItem->id()); ?>"
        class="btn btn-danger btn-sm">Entfernen</a>
    </li>
        <?php else : ?>
            <?= '( ' . htmlspecialchars($cardItem->quantity()) . 'x )'; ?>
        <?php endif ?>
    <?php endforeach; ?>
  </ul>

    <?php if ($purchase->status() === 'pending' && !User::isAdmin()) : ?>
  <div class="purchase-actions">
    <a href="./index.php?area=purchase&action=place&id=<?= htmlspecialchars($purchase->id()); ?>"
      class="btn btn-success">Bestellung tätigen</a>
  </div>
    <?php endif ?>
<?php else : ?>
  <p> Noch keine Artikel im Warenkorb </p>
<?php endif ?>
