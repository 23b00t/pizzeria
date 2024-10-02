<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
$csrf_token = Helper::generateCSRFToken(); 
?>

<?php 
$pageTitle = isset($ingredient) ? 'Zutaten bearbeiten' : 'Neue Zutaten erstellen'; 
require __DIR__ . '/../head.php'; 
?>
    <div class="container mt-5">
        <h1 class="mb-4"><?= isset($ingredient) ? 'Zutaten bearbeiten' : 'Neue Zutaten erstellen' ?></h1>
        
        <form action="<?= isset($ingredient) ? './index.php?ingredient/update/' . htmlspecialchars($ingredient->id()) : './index.php?ingredient/store' ?>" method="POST">
            <div class="form-group mb-3">
                <label for="name">Zutaten Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= isset($ingredient) ? htmlspecialchars($ingredient->name()) : '' ?>" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="price">Preis (€)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= isset($ingredient) ? htmlspecialchars($ingredient->price()) : '' ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="vegetarian">Vegetarisch</label>
                <input type="checkbox" name="vegetarian" id="vegetarian" class="form-check-input" 
                       <?= (isset($ingredient) && $ingredient->vegetarian()) ? 'checked' : '' ?>>
                <label class="form-check-label" for="vegetarian">Diese Zutat ist vegetarisch</label>
            </div>
            <!-- csrf_token einfügen -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <button type="submit" class="btn btn-primary"><?= isset($ingredient) ? 'Zutaten aktualisieren' : 'Zutaten erstellen' ?></button>
            <a href="./index.php?ingredient/index" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>
<?php require __DIR__ . '/../tail.php'; ?>
