<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
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
                            <a href="./index.php?pizza/edit/<?= htmlspecialchars($pizza->id()) ?>" class="btn btn-warning btn-sm">Bearbeiten</a>
                            <a href="./index.php?pizza/delete/<?= htmlspecialchars($pizza->id()) ?>" class="btn btn-danger btn-sm">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="./index.php?pizza/create" class="btn btn-primary">Neue Pizza hinzufügen</a>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
