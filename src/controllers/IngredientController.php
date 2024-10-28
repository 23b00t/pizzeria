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
 * - index(): array: Displays a list of all ingredients available in the system.
 * - edit(int $id): array: Renders the form for editing an existing ingredient.
 * - create(): array: Renders the form for creating a new ingredient.
 * - store(array $formData): array: Validates the provided form data and saves a new ingredient to the database.
 * - update(int $id, array $formData): array: Validates form data and updates the specified ingredient in the database.
 * - delete(int $id): array: Deletes the ingredient identified by the specified ID from the database.
 */
class IngredientController
{
    private string $area;
    private string $action;
    private string $view;
    private bool $redirect;
    private string $msg;

    /**
     * @param string $area
     * @param string $action
     * @param string $view
     * @param bool $redirect
     * @param string $msg
     */
    public function __construct(string &$area, string &$action, string &$view, bool &$redirect, string &$msg)
    {
        $this->area = &$area;
        $this->action = &$action;
        $this->view = &$view;
        $this->redirect = &$redirect;
        $this->msg = &$msg;
    }

    /**
     * Display a list of all ingredients.
     *
     * This method retrieves all ingredients from the database and includes
     * the view to display them in a list format.
     * @return array
     */
    public function index(): array
    {
        $ingredients = Ingredient::findAll();

        $this->view = 'ingredient/index';
        return [ 'ingredients' => $ingredients ];
    }

    /**
     * Render the edit form for a specified ingredient.
     *
     * This method retrieves the ingredient by its ID and includes the
     * form view for editing the ingredient's details.
     *
     * @param int $id The ID of the ingredient to edit.
     * @return array
     */
    public function edit(int $id): array
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $ingredient = Ingredient::findBy($id, 'id');

        $this->view = 'ingredient/form';
        if ($ingredient) {
            return [ 'ingredient' => $ingredient ];
        }
    }

    /**
     * Display the form for creating a new ingredient.
     *
     * This method includes the form view for the creation of a new ingredient.
     * @return void
     */
    public function create(): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $this->view = 'ingredient/form';
    }

    /**
     * Store a new ingredient in the database.
     *
     * This method validates the form data submitted for creating a new
     * ingredient, instantiates the Ingredient model, and saves it to the
     * database. It handles redirection upon success or failure.
     *
     * @param array $formData The form data submitted for creating the ingredient.
     * @return void
     */
    public function store(array $formData): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        // Validate form data
        $vegetarian = isset($formData['vegetarian']) ? 1 : 0;
        $ingredient = new Ingredient($formData['name'], $formData['price'], $vegetarian);

        try {
            // Save the new ingredient
            $ingredient->save();

            // Redirect to the ingredient list with a success message
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'msg=Erfolgreich erstellt';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            // Handle error and redirect back to the form
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Fehler';
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
     * @return void
     */
    public function update(int $id, array $formData): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $ingredient = Ingredient::findBy($id, 'id');

        if ($ingredient) {
            // Update the ingredient's properties
            $ingredient->name($formData['name']);
            $ingredient->price($formData['price']);
            $ingredient->vegetarian(isset($formData['vegetarian']) ? 1 : 0);

            try {
                // Save the updated ingredient to the database
                $ingredient->update();
                $this->redirect = true;
                $this->area = 'ingredient';
                $this->action = 'index';
                $this->msg = 'msg=Erfolgreich aktualisiert';
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                $this->redirect = true;
                $this->area = 'ingredient';
                $this->action = 'index';
                $this->msg = 'error=Fehler';
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
     * @return void
     */
    public function delete(int $id): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'ingredient';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $ingredient = Ingredient::findBy($id, 'id');

        if ($ingredient) {
            try {
                // Delete the ingredient from the database
                $ingredient->delete();
                $this->redirect = true;
                $this->area = 'ingredient';
                $this->action = 'index';
                $this->msg = 'msg=Erfolgreich gelöscht';
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                $this->redirect = true;
                $this->area = 'ingredient';
                $this->action = 'index';
                $this->msg = 'error=Fehler';
            }
        }
    }
}
