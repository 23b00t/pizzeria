<?php

namespace app\controllers;

use app\models\User;
use app\models\Ingredient;
use PDOException;

/**
 * IngredientController class responsible for managing ingredient-related actions,
 * such as displaying, creating, updating, and deleting ingredients.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all ingredients available in the system.
 * - edit(int $id): void: Renders the form for editing an existing ingredient.
 * - create(): void: Renders the form for creating a new ingredient.
 * - store(array $formData): void: Validates the provided form data and saves a new ingredient to the database.
 * - update(int $id, array $formData): void: Validates the form data and updates the specified ingredient in the database.
 * - delete(int $id): void: Deletes the ingredient identified by the specified ID from the database.
 */
class IngredientController
{
    /**
     * Display a list of all ingredients.
     *
     * This method retrieves all ingredients from the database and includes
     * the view to display them in a list format.
     */
    public function index(): void
    {
        $ingredients = Ingredient::findAll(); 

        // Include the view to display all ingredients
        include __DIR__ . '/../views/ingredient/index.php'; 
    }

    /**
     * Render the edit form for a specified ingredient.
     *
     * This method retrieves the ingredient by its ID and includes the 
     * form view for editing the ingredient's details.
     *
     * @param int $id The ID of the ingredient to edit.
     */
    public function edit(int $id): void
    {
        if (!User::isAdmin()) return;

        $ingredient = Ingredient::findBy($id, 'id');

        if ($ingredient) {
            // Include the ingredient form view for editing
            include './views/ingredient/form.php'; 
        } 
    }

    /**
     * Display the form for creating a new ingredient.
     *
     * This method includes the form view for the creation of a new ingredient.
     */
    public function create(): void
    {
        if (!User::isAdmin()) return;

        include __DIR__ . '/../views/ingredient/form.php';
    }

    /**
     * Store a new ingredient in the database.
     *
     * This method validates the form data submitted for creating a new
     * ingredient, instantiates the Ingredient model, and saves it to the
     * database. It handles redirection upon success or failure.
     *
     * @param array $formData The form data submitted for creating the ingredient.
     */
    public function store(array $formData): void
    {
        if (!User::isAdmin()) return;

        // Validate form data
        $vegetarian = isset($formData['vegetarian']) ? 1 : 0;
        $ingredient = new Ingredient($formData['name'], $formData['price'], $vegetarian);

        try {
            // Save the new ingredient
            $ingredient->save();

            // Redirect to the ingredient list with a success message
            header('Location: ./index.php?ingredient/index?msg=Ingredient%20successfully%20created');
            exit();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Handle error and redirect back to the form
            header('Location: ./index.php?ingredient/index?error=Could%20not%20create%20ingredient');
            exit();
        }
    }

    /**
     * Handle the update process for an existing ingredient.
     *
     * This method retrieves the ingredient by its ID, validates the provided
     * form data, updates the ingredient's properties, and saves the changes
     * to the database. It manages redirection upon success or failure.
     *
     * @param int   $id       The ingredient ID to update.
     * @param array $formData The form data submitted for updating the ingredient.
     */
    public function update(int $id, array $formData): void
    {
        if (!User::isAdmin()) return;

        $ingredient = Ingredient::findBy($id, 'id');

        if ($ingredient) {
            // Update the ingredient's properties
            $ingredient->name($formData['name']);
            $ingredient->price($formData['price']);
            $ingredient->vegetarian(isset($formData['vegetarian']) ? 1 : 0);

            try {
                // Save the updated ingredient to the database
                $ingredient->update(); 
                header('Location: ./index.php?ingredient/index?msg=Ingredient%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?ingredient/index?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Delete the ingredient with the specified ID.
     *
     * This method retrieves the ingredient by its ID and attempts to delete it
     * from the database. It handles redirection and manages any errors
     * that may occur during the deletion process.
     *
     * @param int $id The ingredient ID.
     */
    public function delete(int $id): void
    {
        if (!User::isAdmin()) return;

        $ingredient = Ingredient::findBy($id, 'id');

        if ($ingredient) {
            try {
                // Delete the ingredient from the database
                $ingredient->delete();
                header('Location: ./index.php?ingredient/index?msg=Ingredient%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?ingredient/index?msg=error');
                exit();
            }
        }
    }
}
