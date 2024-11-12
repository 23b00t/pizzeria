<?php

namespace app\controllers;

use app\core\Response;
use app\models\Ingredient;

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
class IngredientController extends BaseController
{
    /**
     * Display a list of all ingredients.
     *
     * This method retrieves all ingredients from the database and includes
     * the view to display them in a list format.
     * @return Response
     */
    public function index(): Response
    {
        $ingredients = Ingredient::findAll();

        return new Response([ 'ingredients' => $ingredients ], 'ingredient/index');
    }

    /**
     * Render the edit form for a specified ingredient.
     *
     * This method retrieves the ingredient by its ID and includes the
     * form view for editing the ingredient's details.
     *
     * @param int $id The ID of the ingredient to edit.
     * @return Response
     */
    public function edit(int $id): Response
    {
        $this->authorize();
        $ingredient = Ingredient::findBy($id, 'id');

        return new Response([ 'ingredient' => $ingredient ], 'ingredient/form');
    }

    /**
     * Display the form for creating a new ingredient.
     *
     * This method includes the form view for the creation of a new ingredient.
     * @return Response
     */
    public function create(): Response
    {
        $this->authorize();
        return new Response([], 'ingredient/form');
    }

    /**
     * Store a new ingredient in the database.
     *
     * This method validates the form data submitted for creating a new
     * ingredient, instantiates the Ingredient model, and saves it to the
     * database. It handles redirection upon success or failure.
     *
     * @param array $formData The form data submitted for creating the ingredient.
     * @return Response
     */
    public function store(array $formData): Response
    {
        $this->authorize();

        return $this->handleDatabaseOperation(function () use ($formData) {
            $vegetarian = isset($formData['vegetarian']) ? 1 : 0;
            $ingredient = new Ingredient($formData['name'], $formData['price'], $vegetarian);

            // Save the new ingredient
            $ingredient->save();

            // Redirect to the ingredient list with a success message
            $response = $this->index();
            $response->setMsg('msg=Erfolgreich erstellt');
            return $response;
        }, $this);
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
     * @return Response
     */
    public function update(int $id, array $formData): Response
    {
        $this->authorize();

        return $this->handleDatabaseOperation(function () use ($formData, $id) {
            $ingredient = Ingredient::findBy($id, 'id');

            // Update the ingredient's properties
            $ingredient->name($formData['name']);
            $ingredient->price($formData['price']);
            $ingredient->vegetarian(isset($formData['vegetarian']) ? 1 : 0);

            // Save the updated ingredient to the database
            $ingredient->update();
            $response = $this->index();
            $response->setMsg('msg=Erfolgreich aktualisiert');
            return $response;
        }, $this);
    }

    /**
     * Delete the ingredient with the specified ID.
     *
     * This method retrieves the ingredient by its ID and attempts to delete it
     * from the database. It handles redirection and manages any errors
     * that may occur during the deletion process.
     *
     * @param int $id The ingredient ID.
     * @return Response
     */
    public function delete(int $id): Response
    {
        $this->authorize();

        return $this->handleDatabaseOperation(function () use ($id) {
            $ingredient = Ingredient::findBy($id, 'id');

            // Delete the ingredient from the database
            $ingredient->delete();
            $response = $this->index();
            $response->setMsg('msg=Erfolgreich gelöscht');
            return $response;
        }, $this);
    }
}
