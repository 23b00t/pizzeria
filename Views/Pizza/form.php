<?php 
require_once __DIR__ . '/../../Helpers/Helper.php';
Helper::validateSession();
?>

<?php 
$pageTitle = isset($pizza) ? 'Pizza bearbeiten' : 'Neue Pizza erstellen'; 
require __DIR__ . '/../head.php'; 
?>
    <div class="container mt-5">
        <h1 class="mb-4"><?= isset($pizza) ? 'Pizza bearbeiten' : 'Neue Pizza erstellen' ?></h1>
        
        <form action="<?= isset($pizza) ? './index.php?Pizza/update/' . htmlspecialchars($pizza->id()) : './index.php?Pizza/store' ?>" method="POST">
            <div class="form-group mb-3">
                <label for="name">Pizza Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= isset($pizza) ? htmlspecialchars($pizza->name()) : '' ?>" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="price">Preis (€)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= isset($pizza) ? htmlspecialchars($pizza->price()) : '' ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary"><?= isset($pizza) ? 'Pizza aktualisieren' : 'Pizza erstellen' ?></button>
            <a href="./index.php?Pizza/index" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
