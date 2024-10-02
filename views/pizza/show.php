<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
?>

<?php $pageTitle = 'Pizzen'; require __DIR__ . '/../head.php'; ?>
    <div class="container">
        <h1><?= htmlspecialchars($pizza->name()); ?></h1>
        <div class="pizza-details">
            <p><strong>Name:</strong> <?= htmlspecialchars($pizza->name()); ?></p>
            <p><strong>Preis:</strong> <?= htmlspecialchars($pizza->price()); ?> €</p>
        </div>
        <a href="./index.php?pizza/index" class="button">Zurück zur Übersicht</a>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
