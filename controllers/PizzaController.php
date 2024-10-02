<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';
require_once __DIR__ . '/../models/Pizza.php';

/**
 * PizzaController class responsible for managing pizza-related actions,
 * such as displaying pizza details, handling pizza creation, updating,
 * and deletion.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all pizzas available in the system.
 * - show(int $id): void: Displays detailed information about a specific pizza based on the given ID.
 * - create(): void: Renders the form for creating a new pizza.
 * - store(array $formData): void: Validates the provided form data and saves a new pizza to the database.
 * - edit(int $id): void: Retrieves the specified pizza by ID and renders the edit form for that pizza.
 * - update(int $id, array $formData): void: Validates the provided form data and updates the pizza with the given ID.
 * - delete(int $id): void: Deletes the pizza identified by the specified ID from the database.
 */
class PizzaController
{
    /**
     * Display a list of all pizzas.
     *
     * This method retrieves all pizzas from the database and includes
     * the corresponding view to display them in a list format.
     */
    public function index(): void
    {
        $pizzas = Pizza::findAll(); 

        // Include the view to display all pizzas
        include __DIR__ . '/../views/pizza/index.php'; 
    }

    /**
     * Show detailed information about a specific pizza.
     *
     * This method retrieves the pizza by its ID and any associated ingredients,
     * then includes the pizza detail view to display the information.
     *
     * @param int $id The pizza ID.
     */
    public function show($id): void
    {
        $pizza = Pizza::findBy($id, 'id');
        $ingredients = Pizza::findIngredientsByPizzaId($id);

        if ($pizza) {
            // Include the pizza detail view and pass the pizza object
            include './views/pizza/show.php'; 
        } 
    }

    /**
     * Render the edit form for a specified pizza.
     *
     * This method retrieves the pizza by its ID and includes the 
     * form view for editing the pizza's details.
     *
     * @param int $id The ID of the pizza to edit.
     */
    public function edit($id): void
    {
        $pizza = Pizza::findBy($id, 'id');

        if ($pizza) {
            // Include the pizza detail view and pass the pizza object
            include './views/pizza/form.php'; 
        } 
    }

    /**
     * Display the form for creating a new pizza.
     *
     * This method includes the form view for the creation of a new pizza
     * without any pre-filled data.
     */
    public function create(): void
    {
        include __DIR__ . '/../views/pizza/form.php';
    }

    /**
     * Store a new pizza in the database.
     *
     * This method validates the form data submitted for creating a new
     * pizza, instantiates the Pizza model, and saves it to the
     * database. It handles the redirection upon success or failure.
     *
     * @param array $formData The form data submitted for creating the pizza.
     */
    public function store($formData): void
    {
        // TODO: Validation of form data
        $pizza = new Pizza($formData['name'], $formData['price']);

        try {
            // Save the new pizza
            $pizza->save();

            // Redirect to the pizza list with a success message
            header('Location: ./index.php?pizza/index?msg=Pizza%20successfully%20created');
            exit();
        } catch (PDOException $e) {
            // Handle the error and redirect back to the form
            header('Location: ./index.php?pizza/show/' . $id . '?error=Could%20not%20create%20pizza');
            exit();
        }
    }

    /**
     * Handle the update process for an existing pizza.
     *
     * This method retrieves the pizza by its ID, validates the provided
     * form data, updates the pizza's properties, and saves the changes
     * to the database. It also manages redirection upon success or failure.
     *
     * @param int $id The pizza ID to update.
     * @param array $formData The form data submitted for updating the pizza.
     */
    public function update($id, $formData): void
    {
        $pizza = Pizza::findBy($id, 'id');

        if ($pizza) {
            // Update the pizza properties
            $pizza->name($formData['name']);
            $pizza->price($formData['price']);

            try {
                // Save the updated pizza to the database
                $pizza->update(); 
                header('Location: ./index.php?pizza/show/' . $id . '?msg=Pizza%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                header('Location: ./index.php?pizza/show/' . $id . '?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Delete the pizza with the specified ID.
     *
     * This method retrieves the pizza by its ID and attempts to delete it
     * from the database. It manages the redirection and handles any errors
     * that may occur during the deletion process.
     *
     * @param int $id The pizza ID.
     */
    public function delete($id): void
    {
        $pizza = Pizza::findBy($id, 'id');

        if ($pizza) {
            try {
                $pizza->delete();
                header('Location: ./index.php?pizza/index?msg=Pizza%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                // Handle errors as needed
                header('Location: ./index.php?pizza/index?msg=error');
                exit();
            }
        }
    }
}
