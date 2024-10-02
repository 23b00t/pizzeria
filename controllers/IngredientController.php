<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';
require_once __DIR__ . '/../models/Ingredient.php';

/**
 * IngredientController class responsible for managing ingredient-related actions, such as
 * displaying ingredient details, handling ingredient creation, updating, and deletion.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all ingredients.
 * - show(int $id): void: Displays ingredient details based on the given ID.
 * - create(array $formData): void: Validates and creates a new ingredient from the provided form data.
 * - store(Ingredient $ingredient): void: Saves the ingredient object to the database.
 * - edit(int $id): void: Displays the edit form for the specified ingredient.
 * - update(int $id, array $formData): void: Validates and updates the ingredient with the given ID.
 * - delete(int $id): void: Deletes the ingredient with the specified ID.
 */
class IngredientController
{
    /**
     * Display a list of all ingredients.
     */
    public function index(): void
    {
        $ingredients = Ingredient::findAll(); 

        // Include the view to display all ingredients
        include __DIR__ . '/../views/ingredient/index.php'; 
    }

    public function edit($id): void
    {
        $ingredient = Ingredient::findById($id);

        if ($ingredient) {
            // Include the ingredient detail view and pass the ingredient object
            include './views/ingredient/form.php'; 
        } 
    }

    /**
     * Handle the ingredient creation process.
     * 
     * @param array $formData The form data submitted for creating a ingredient.
     */
    public function create(): void
    {
        include __DIR__ . '/../views/ingredient/form.php';
    }

    /**
     * Store a new ingredient in the database.
     * 
     * @param array $formData The form data submitted for creating the ingredient.
     */
    public function store($formData): void
    {
        // TODO: Validation of form data
        $vegetarian = (isset($formData['vegetarian']) ? 1 : 0);
        $ingredient = new Ingredient($formData['name'], $formData['price'], $vegetarian);

        try {
            // Save the new ingredient
            $ingredient->save();

            // Redirect to the ingredient list with a success message
            header('Location: ./index.php?ingredient/index?msg=Ingredient%20successfully%20created');
            exit();
        } catch (PDOException $e) {
            // Handle the error and redirect back to the form
            header('Location: ./index.php?ingredient/show/' . $id . '?error=Could%20not%20create%20ingredient');
            exit();
        }
    }

    /**
     * Handle the ingredient update process.
     * 
     * @param int $id The ingredient ID to update.
     * @param array $formData The form data submitted for updating the ingredient.
     */
    public function update($id, $formData): void
    {
        $ingredient = Ingredient::findById($id);

        if ($ingredient) {
            // Update the ingredient properties
            $ingredient->name($formData['name']);
            $ingredient->price($formData['price']);
            $ingredient->vegetarian(isset($formData['vegetarian']) ? 1 : 0);

            try {
                // Save the updated ingredient to the database
                $ingredient->update(); 
                header('Location: ./index.php?ingredient/index?msg=Ingredient%20erfolgreich%20aktualisiert');
                exit();
            } catch (PDOException $e) {
                header('Location: ./index.php?ingredient/index?msg=Fehler');
                exit();
            }
        } 
    }

    /**
     * Delete the ingredient with the specified ID.
     * 
     * @param int $id The ingredient ID.
     */
    public function delete($id): void
    {
        $ingredient = Ingredient::findById($id);

        if ($ingredient) {
            try {
                $ingredient->delete(); // Assuming a delete method exists in the Ingredient class
                header('Location: ./index.php?ingredient/index?msg=Ingredient%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                // Handle errors as needed
                header('Location: ./index.php?ingredient/index?msg=error');
                exit();
            }
        }
    }
}
