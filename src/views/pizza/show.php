<?php 

namespace app\views\pizza;

use app\helpers\Helper;
Helper::validateSession();

// @var controllers\PizzaController $ingredients
// @var controllers\PizzaController $pizza
?>

<?php $pageTitle = 'Pizzen'; require __DIR__ . '/../head.php'; ?>
<div class="container">
    <h1><?= htmlspecialchars($pizza->name()); ?></h1>
    <div class="pizza-details">
        <p><strong>Name:</strong> <?= htmlspecialchars($pizza->name()); ?></p>
        <p><strong>Preis:</strong> <?= htmlspecialchars($pizza->price()); ?> €</p>
    </div>

    <h2>Zutaten:</h2>
    <ul>
        <?php foreach ($ingredients as $ingredientData): ?>
            <li>
                <?= htmlspecialchars($ingredientData['ingredient']->name()); ?> 
                (Menge: <?= htmlspecialchars($ingredientData['quantity']); ?>) 
                <?= $ingredientData['ingredient']->vegetarian() ? '(Vegetarisch)' : '(Nicht vegetarisch)'; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="./index.php?pizza/index" class="button">Zurück zur Übersicht</a>
</div>
<?php require __DIR__ . '/../tail.php'; ?>
