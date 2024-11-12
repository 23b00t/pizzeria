<?php

namespace app\controllers;

use app\core\Response;
use app\models\User;
use app\models\Purchase;
use app\models\Card;
use Exception;

/**
 * PurchaseController class responsible for managing purchase-related actions,
 * such as displaying purchase details, handling purchase creation, updating,
 * and deletion.
 *
 * Methods:
 *
 * - index(): array: Displays a list of all purchases available in the system.
 * - handle(array $formData): array: Handles the creation of a new purchase and adds items to it.
 * - place(int $id): array: Places an order for the specified purchase by updating its status.
 * - update(int $id): array: Updates the status of the specified purchase to "delivered".
 * - delete(int $id): array: Deletes the purchase identified by the specified ID from the database.
 */
class PurchaseController extends BaseController
{
    /**
     * Display a list of all purchases.
     *
     * This method fetches all purchases from the database and includes
     * a view to display them. The $purchases variable is passed to the view
     * for rendering.
     *
     * @var Purchase[] $purchases Array of purchases used in the included view.
     * @return Response
     */
    public function index(): Response
    {
        if (User::isAdmin()) {
            // Retrieve all purchases from the database
            $purchases = Purchase::findAll();
        } else {
            // Retrieve purchases belonging to the logged-in user
            $purchases = Purchase::where('user_id = ?', [$_SESSION['login']]);
        }

        return new Response([ 'purchases' => $purchases ], 'purchase/index');
    }

    /**
     * Handle the creation of a new purchase and add items to it.
     *
     * This method first checks if a purchase ID already exists in the session.
     * If not, a new purchase is created for the logged-in user. Then, a new card
     * (item) is created and associated with the purchase, and the card is stored
     * in the session.
     *
     * @param array $formData The form data that contains the pizza ID and quantity for the card.
     * @return Response
     */
    public function handle(array $formData): Response
    {
        $user_id = $_SESSION['login'];

        return $this->handleDatabaseOperation(function () use ($user_id, $formData) {
            if (!isset($_SESSION['purchase_id'])) {
                $purchase = new Purchase($user_id);
                $purchase->save();

                $purchase = Purchase::where('user_id = ? ORDER BY id DESC LIMIT 1', [$user_id])[0];
                $_SESSION['purchase_id'] = $purchase->id();
            }

            $purchase_id = $_SESSION['purchase_id'];
            $card = new Card($formData['pizza_id'], $purchase_id, $formData['quantity']);
            $card->save();

            $card = Card::where('purchase_id = ? ORDER BY id DESC LIMIT 1', [$purchase_id])[0];
            $_SESSION['card'][] = $card;

            $response = (new PizzaController())->index();
            $response->setMsg('msg=Item added to cart');
            return $response;
        }, new PizzaController());
    }

    /**
     * Handle the order process for an existing purchase.
     *
     * This method sets the status of the purchase to "placed" and saves it.
     * It manages the redirection and handles any errors that may occur.
     *
     * @param int $id The purchase ID to place the order for.
     * @return Response
     */
    public function place(int $id): Response
    {
        return $this->handleDatabaseOperation(function () use ($id) {
            $purchase = Purchase::findBy($id, 'id');
            $purchase->status('placed');
            $purchase->update();

            $response = (new CardController())->index();
            $response->setMsg('msg=Order placed successfully');
            return $response;
        }, new CardController());
    }

    /**
     * Handle the update process for an existing purchase.
     *
     * This method updates the status of the purchase to "delivered" and saves
     * the changes to the database. It also manages redirection upon success or failure.
     *
     * @param int $id The purchase ID to update.
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!User::isAdmin()) {
            throw new Exception('Action not allowed!');
        }

        return $this->handleDatabaseOperation(function () use ($id) {
            $purchase = Purchase::findBy($id, 'id');
            $purchase->status('delivered');
            $purchase->update();

            $response = (new PurchaseController())->index();
            $response->setMsg('msg=Successfully updated');
            return $response;
        }, new PurchaseController());
    }

    /**
     * Delete the purchase with the specified ID.
     *
     * This method retrieves the purchase by its ID and attempts to delete it
     * from the database. If successful, it clears any related session data
     * and redirects to the index with a success message.
     *
     * @param int $id The purchase ID.
     * @return Response
     */
    public function delete(int $id): Response
    {
        return $this->handleDatabaseOperation(function () use ($id) {
            $purchase = Purchase::findBy($id, 'id');
            $purchase->delete();

            unset($_SESSION['card'], $_SESSION['purchase_id']);

            $response = (new PurchaseController())->index();
            $response->setMsg('msg=Successfully deleted');
            return $response;
        }, new PurchaseController());
    }
}
