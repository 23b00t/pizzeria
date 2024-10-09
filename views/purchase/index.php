<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
/** @var controllers\PurchaseController $purchases */
?>


<?php $pageTitle = 'Pizzen'; require __DIR__ . '/../head.php'; ?>
    <div class="container mt-5">
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
                <?php foreach ($purchases as $purchase): ?>
                    <tr>
                        <td><a href="./index.php?card/show/<?= htmlspecialchars($purchase->id()) ?>"><?= htmlspecialchars($purchase->id()) ?></a></td>
                        <td><?= htmlspecialchars($purchase->purchased_at()) ?? 'offen' ?></td>
                        <td><?= htmlspecialchars($purchase->delivered_at()) ?? 'offen' ?></td>
                        <td><?= htmlspecialchars($purchase->status()) ?></td>
                        <td>
                        <?php if (User::isAdmin()): ?>
                            <a href="./index.php?purchase/edit/<?= htmlspecialchars($purchase->id()) ?>" class="btn btn-warning btn-sm">Bearbeiten</a>
                            <a href="./index.php?purchase/delete/<?= htmlspecialchars($purchase->id()) ?>" class="btn btn-danger btn-sm">Löschen</a>
                            <a href="./index.php?purchase/update/<?= htmlspecialchars($purchase->id()) ?>" class="btn btn-success btn-sm">Geliefert</a>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
