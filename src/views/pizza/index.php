<?php 

namespace app\views\pizza;

use app\helpers\Helper;
use app\models\User;
Helper::validateSession();
$csrf_token = Helper::generateCSRFToken(); 
/** @var controllers\PizzaController $pizzas */
?>

<?php $pageTitle = 'Pizzen'; require __DIR__ . '/../head.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Pizza-Liste</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Preis</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pizzas as $pizza): ?>
                    <tr>
                        <td><?= htmlspecialchars($pizza->id()) ?></td>
                        <td><a href="./index.php?pizza/show/<?= htmlspecialchars($pizza->id()) ?>"><?= htmlspecialchars($pizza->name()) ?></a></td>
                        <td><?= htmlspecialchars($pizza->price()) ?> €</td>
                        <td>

                        <?php if (User::isAdmin()): ?>
                            <a href="./index.php?pizza/edit/<?= htmlspecialchars($pizza->id()) ?>" class="btn btn-warning btn-sm">Bearbeiten</a>
                            <a href="./index.php?pizza/delete/<?= htmlspecialchars($pizza->id()) ?>" class="btn btn-danger btn-sm">Löschen</a>
                        <?php endif; ?>
                        <?php if (!User::isAdmin()): ?>
                            <!-- purchase form -->
                            <form action="./index.php?purchase/handle/" method="POST" style="display:inline;">
                                <input type="hidden" name="pizza_id" value="<?= htmlspecialchars($pizza->id()) ?>">
                                <input type="number" name="quantity" min="1" value="1" class="form-control-sm" style="width: 80px; display:inline-block;" required>
                                <!-- insert csrf_token -->
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Warenkorb hinzufügen</button>
                            </form>
                            <!-- end purchase form -->
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (User::isAdmin()): ?>
            <a href="./index.php?pizza/create" class="btn btn-primary">Neue Pizza hinzufügen</a>
        <?php endif; ?>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
