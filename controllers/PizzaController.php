<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';
require_once __DIR__ . '/../models/Pizza.php';

/**
 * PizzaController class responsible for managing pizza-related actions, such as
 * displaying pizza details, handling pizza creation, updating, and deletion.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all pizzas.
 * - show(int $id): void: Displays pizza details based on the given ID.
 * - create(array $formData): void: Validates and creates a new pizza from the provided form data.
 * - store(Pizza $pizza): void: Saves the pizza object to the database.
 * - edit(int $id): void: Displays the edit form for the specified pizza.
 * - update(int $id, array $formData): void: Validates and updates the pizza with the given ID.
 * - delete(int $id): void: Deletes the pizza with the specified ID.
 */
class PizzaController
{
    /**
     * Display a list of all pizzas.
     */
    public function index(): void
    {
        $pizzas = Pizza::findAll(); 

        // Include the view to display all pizzas
        include __DIR__ . '/../views/pizza/index.php'; 
    }

    /**
     * Show pizza details.
     * 
     * @param int $id The pizza ID.
     */
    public function show($id): void
    {
        $pizza = Pizza::findById($id);
        $ingredients = Pizza::findIngredientsByPizzaId($id);

        if ($pizza) {
            // Include the pizza detail view and pass the pizza object
            include './views/pizza/show.php'; 
        } 
    }

    public function edit($id): void
    {
        $pizza = Pizza::findById($id);

        if ($pizza) {
            // Include the pizza detail view and pass the pizza object
            include './views/pizza/form.php'; 
        } 
    }

    /**
     * Handle the pizza creation process.
     * 
     * @param array $formData The form data submitted for creating a pizza.
     */
    public function create(): void
    {
        include __DIR__ . '/../views/pizza/form.php';
    }

    /**
     * Store a new pizza in the database.
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
     * Handle the pizza update process.
     * 
     * @param int $id The pizza ID to update.
     * @param array $formData The form data submitted for updating the pizza.
     */
    public function update($id, $formData): void
    {
        $pizza = Pizza::findById($id);

        if ($pizza) {
            // Update the pizza properties
            $pizza->name($formData['name']);
            $pizza->price($formData['price']);

            try {
                // Save the updated pizza to the database
                $pizza->update(); 
                header('Location: ./index.php?pizza/show/' . $id . '?msg=Pizza%20erfolgreich%20aktualisiert');
                exit();
            } catch (PDOException $e) {
                header('Location: ./index.php?pizza/show/' . $id . '?msg=Fehler');
                exit();
            }
        } 
    }

    /**
     * Delete the pizza with the specified ID.
     * 
     * @param int $id The pizza ID.
     */
    public function delete($id): void
    {
        $pizza = Pizza::findById($id);

        if ($pizza) {
            try {
                $pizza->delete(); // Assuming a delete method exists in the Pizza class
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
