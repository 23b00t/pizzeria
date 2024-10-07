<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
$csrf_token = Helper::generateCSRFToken(); 

// @var controllers\PurchaseController $purchase
// @var controllers\CardController $cards
?>

<?php $pageTitle = 'Bestellung'; require __DIR__ . '/../head.php'; ?>
<div class="container">
    <?php if (!empty($cards)): ?>
        <h1>Bestellung #<?= htmlspecialchars($purchase->id()); ?></h1>
        <div class="purchase-details">
            <p><strong>Bestelldatum:</strong> <?= htmlspecialchars($purchase->purchased_at()); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($purchase->status()); ?></p>
        </div>

        <h2>Pizzen in der Bestellung:</h2>
        <ul>
            <?php foreach ($cards as $cardItem): ?>
                <li>
                    <strong>
                        <a href="./index.php?pizza/show/<?= htmlspecialchars($cardItem->pizza_id()) ?>"><?= htmlspecialchars(Pizza::findBy($cardItem->pizza_id(), 'id')->name()) ?></a>
                    </strong> 
                <?php if ($purchase->status() === 'pending' ): ?>
                    (Anzahl: 
                    <form action="./index.php?card/update" method="POST" style="display:inline;">
                        <input type="number" name="quantity" value="<?= htmlspecialchars($cardItem->quantity()); ?>" min="1" required style="width: 60px;">
                        <!-- insert csrf_token -->
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <!-- Add hidden field for card ID -->
                        <input type="hidden" name="card_id" value="<?= htmlspecialchars($cardItem->id()); ?>">
                        <button type="submit" class="btn btn-sm btn-primary">Ändern</button>
                    </form>
                    )
                    <a href="./index.php?card/delete/<?= htmlspecialchars($cardItem->id()); ?>" class="btn btn-danger btn-sm">Entfernen</a>
                </li>
                <?php else: ?>
                    <?= '( ' . htmlspecialchars($cardItem->quantity()) . 'x )'; ?>
                <?php endif ?>
            <?php endforeach; ?>
        </ul>

        <?php if ($purchase->status() === 'pending' ): ?>
            <div class="purchase-actions">
                <a href="./index.php?purchase/place/<?= htmlspecialchars($purchase->id()); ?>" class="btn btn-success">Bestellung tätigen</a>
                <a href="./index.php?purchase/delete/<?= htmlspecialchars($purchase->id()); ?>" class="btn btn-danger">Bestellung verwerfen</a>
            </div>
        <?php else: ?>
            <p> Bestellung getätigt </p>
        <?php endif ?>
    <?php else: ?>
        <p> Noch keine Artikel im Warenkorb </p>
    <?php endif ?>

    <a href="./index.php?pizza/index" class="button">Zurück zur Übersicht</a>
</div>
<?php require __DIR__ . '/../tail.php'; ?>