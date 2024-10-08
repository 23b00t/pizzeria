<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
$csrf_token = Helper::generateCSRFToken(); 

// @var controllers\PizzaController $pizza
// @var controllers\PizzaController $ingredients

$pageTitle = isset($pizza) ? 'Pizza bearbeiten' : 'Neue Pizza erstellen'; 
require __DIR__ . '/../head.php'; 
?>

<div class="container mt-5">
    <h1 class="mb-4"><?= $pageTitle ?></h1>
    
    <form action="<?= isset($pizza) ? './index.php?pizza/update/' . htmlspecialchars($pizza->id()) : './index.php?pizza/store' ?>" method="POST">
        <div class="form-group mb-3">
            <label for="name">Pizza Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= isset($pizza) ? htmlspecialchars($pizza->name()) : '' ?>" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="price">Preis (€)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= isset($pizza) ? htmlspecialchars($pizza->price()) : '' ?>" required>
        </div>

        <!-- Select ingredient -->
        <div class="form-group mb-3">
            <label for="ingredients">Zutaten auswählen und Stückzahl angeben:</label>
            <?php foreach ($ingredients as $ingredient): 
                $pizzaIngredient = PizzaIngredient::where('ingredient_id = ? && pizza_id = ?', [$ingredient->id(), $pizza->id()])[0] ?? null;
                $isChecked = $pizzaIngredient && $pizzaIngredient->quantity() > 0 ? 'checked' : '';
                $quantity = $pizzaIngredient ? $pizzaIngredient->quantity() : 0;
            ?>
                <div class="form-group mb-2">
                    <input type="checkbox" name="ingredients[<?= $ingredient->id() ?>]" id="ingredient_<?= $ingredient->id() ?>" value="<?= $ingredient->id() ?>" <?= $isChecked ?>>
                    <label for="ingredient_<?= $ingredient->id() ?>">
                        <?= htmlspecialchars($ingredient->name()) ?> (<?= number_format($ingredient->price(), 2) ?>€)
                    </label>
                    <!-- Ingredient quantity --> 
                    <input type="number" name="quantities[<?= $ingredient->id() ?>]" class="form-control mt-1" placeholder="Stückzahl" min="0" value="<?= htmlspecialchars($quantity) ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

        <button type="submit" class="btn btn-primary"><?= isset($pizza) ? 'Pizza aktualisieren' : 'Pizza erstellen' ?></button>
        <a href="./index.php?pizza/index" class="btn btn-secondary">Abbrechen</a>
    </form>
</div>

<?php require __DIR__ . '/../tail.php'; ?>
