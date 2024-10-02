<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
?>

<?php $pageTitle = 'Zutaten'; require __DIR__ . '/../head.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Zutaten-Liste</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Preis</th>
                    <th>Vegetarisch</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($ingredients as $ingredient): ?>
                    <tr>
                        <td><?= htmlspecialchars($ingredient->id()) ?></td>
                        <td><a href="./index.php?ingredient/show/<?= htmlspecialchars($ingredient->id()) ?>"><?= htmlspecialchars($ingredient->name()) ?></a></td>
                        <td><?= htmlspecialchars($ingredient->price()) ?> €</td>
                        <td><?= htmlspecialchars($ingredient->vegetarian() === 1) ? 'X' : ''; ?> </td>
                        <td>
                            <a href="./index.php?ingredient/edit/<?= htmlspecialchars($ingredient->id()) ?>" class="btn btn-warning btn-sm">Bearbeiten</a>
                            <a href="./index.php?ingredient/delete/<?= htmlspecialchars($ingredient->id()) ?>" class="btn btn-danger btn-sm">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="./index.php?ingredient/create" class="btn btn-primary">Neue Zutaten hinzufügen</a>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
