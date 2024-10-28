<?php

namespace app\controllers;

use app\models\User;
use app\models\Pizza;
use app\models\PizzaIngredient;
use app\models\Ingredient;
use PDOException;

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
     * Display a list of all pizzas.
     *
     * This method retrieves all pizzas from the database and includes
     * the view to display them in a list format.
     * @return array
     */
    public function index(): array
    {
        $pizzas = Pizza::findAll();

        $this->view = 'pizza/index';
        return [ 'pizzas' => $pizzas];
    }

    /**
     * Show detailed information about a specific pizza.
     *
     * This method retrieves the pizza by its ID and any associated ingredients,
     * then includes the pizza detail view to display the information.
     *
     * @param int $id The pizza ID.
     * @return array
     */
    public function show(int $id): array
    {
        $pizza = Pizza::findBy($id, 'id');
        $ingredients = Pizza::findIngredientsByPizzaId($id);

        $this->view = 'pizza/show';
        if ($pizza) {
            return [ 'ingredients' => $ingredients, 'pizza' => $pizza ];
        }
    }

    /**
     * Render the edit form for a specified pizza.
     *
     * This method retrieves the pizza by its ID and includes the
     * form view for editing the pizza's details.
     *
     * @param int $id The ID of the pizza to edit.
     * @return array
     */
    public function edit(int $id): array
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $pizza = Pizza::findBy($id, 'id');
        $ingredients = Ingredient::findAll();

        $this->view = 'pizza/form';
        if ($pizza) {
            return [ 'pizza' => $pizza, 'ingredients' => $ingredients ];
        }
    }

    /**
     * Display the form for creating a new pizza.
     *
     * This method includes the form view for the creation of a new pizza
     * without any pre-filled data.
     * @return array
     */
    public function create(): array
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $ingredients = Ingredient::findAll();
        $this->view = 'pizza/form';
        return [ 'ingredients' => $ingredients ];
    }

    /**
     * Store a new pizza in the database.
     *
     * This method validates the form data submitted for creating a new
     * pizza, instantiates the Pizza model, and saves it to the
     * database. It handles redirection upon success or failure.
     *
     * @param array $formData The form data submitted for creating the pizza.
     * @return void
     */
    public function store(array $formData): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        // TODO: Validate form data
        $pizza = new Pizza($formData['name'], $formData['price']);

        try {
            // Save the new pizza
            $pizza->save();

            // Get the last saved pizza
            $pizza = Pizza::where('id ORDER BY id DESC LIMIT 1', [])[0];

            $pizzaIngredients = $formData['quantities'];
            foreach ($pizzaIngredients as $pizzaIngredientId => $quantity) {
                if (empty($quantity)) {
                    continue;
                }

                $pizzaIngredient = new PizzaIngredient($pizza->id(), $pizzaIngredientId, $quantity);
                $pizzaIngredient->save();
            }

            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'msg=Erstellen erfolgreich';
        } catch (PDOException $e) {
            // Handle the error and redirect back to the form
            error_log($e->getMessage());
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Fehler';
        }
    }

    /**
     * Handle the update process for an existing pizza.
     *
     * This method retrieves the pizza by its ID, validates the provided
     * form data, updates the pizza's properties, and saves the changes
     * to the database. It also manages redirection upon success or failure.
     *
     * @param int   $id       The pizza ID to update.
     * @param array $formData The form data submitted for updating the pizza.
     * @return void
     */
    public function update(int $id, array $formData): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $pizza = Pizza::findBy($id, 'id');

        if ($pizza) {
            // Update the pizza properties
            $pizza->name($formData['name']);
            $pizza->price($formData['price']);

            try {
                // Save the updated pizza to the database
                $pizza->update();

                $pizzaIngredients = $formData['quantities'];
                foreach ($pizzaIngredients as $pizzaIngredientId => $quantity) {
                    if (empty($quantity)) {
                        continue;
                    }

                    $pizzaIngredient = PizzaIngredient::where(
                        'ingredient_id = ? && pizza_id = ?',
                        [$pizzaIngredientId, $pizza->id()]
                    )[0];
                    $pizzaIngredient->quantity($quantity);
                    $pizzaIngredient->update();
                }

                $this->redirect = true;
                $this->area = 'pizza';
                $this->action = 'index';
                $this->msg = 'msg=Update erfolgreich';
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $this->redirect = true;
                $this->area = 'pizza';
                $this->action = 'index';
                $this->msg = 'error=Fehler';
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
     * @return void
     */
    public function delete(int $id): void
    {
        if (!User::isAdmin()) {
            $this->redirect = true;
            $this->area = 'pizza';
            $this->action = 'index';
            $this->msg = 'error=Nicht erlaubt';
        }

        $pizza = Pizza::findBy($id, 'id');

        if ($pizza) {
            try {
                // Delete the pizza from the database
                $pizza->delete();
                $this->redirect = true;
                $this->area = 'pizza';
                $this->action = 'index';
                $this->msg = 'msg=Löschen erfolgreich';
            } catch (PDOException $e) {
                error_log($e->getMessage());
                // Handle errors as needed
                $this->redirect = true;
                $this->area = 'pizza';
                $this->action = 'index';
                $this->msg = 'error=Fehler';
            }
        }
    }
}
