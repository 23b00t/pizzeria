<?php

namespace app\controllers;

use app\core\Response;
use app\models\User;
use app\models\Card;
use app\models\Purchase;

/**
 * CardController class responsible for managing card-related actions,
 * such as displaying card details, handling card updates, and deletions.
 */
class CardController extends BaseController
{
    /**
     * Show detailed information about a specific card by purchase ID.
     *
     * Retrieves the purchase record by its ID and any associated cards,
     * then includes the card detail view to display this information.
     *
     * @param int $id The purchase ID.
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        // Retrieve the purchase record using the provided ID
        if (User::isAdmin()) {
            $purchase = Purchase::findBy($id, 'id');
        } else {
            $purchase = Purchase::where('id = ? && user_id = ?', [$id, $_SESSION['login']])[0];
        }

        // Fetch all cards
        $allCards = Card::findAll();

        // Filter cards associated with the provided purchase ID
        $cards = array_filter($allCards, function ($c) use ($id) {
            return $c->purchase_id() == $id;
        });

        return new Response([ 'purchase' => $purchase, 'cards' => $cards ], 'card/show');
    }

    /**
     * Show detailed information about the pending card of the current user.
     *
     * Checks if the user has a pending card in the session and displays
     * its details. If the associated purchase has been delivered, the
     * session is cleared.
     * @return Response
     */
    public function index(): Response
    {
        // Retrieve the card and purchase ID from the session
        $purchase_id = $_SESSION['purchase_id'] ?? 0;
        $cards = Card::where('purchase_id = ?', [$purchase_id]);

        // Retrieve the purchase record using the session's purchase ID
        $purchase = Purchase::findBy($purchase_id, 'id');

        // If the purchase is delivered, clear the session and reset variables
        if (isset($purchase) && $purchase->status() === 'delivered') {
            unset($_SESSION['purchase_id']);
            $purchase = null;
        }

        return new Response([ 'purchase' => $purchase, 'cards' => $cards ], 'card/show');
    }

    /**
     * Handle the update process for an existing card.
     *
     * Retrieves the card by its ID, updates its quantity based on the
     * provided form data, and saves the changes to the database.
     * It also updates the session if necessary.
     *
     * @param array $formData The form data submitted for updating the card.
     * @return Response
     */
    public function update(array $formData): Response
    {
        // Use handleDatabaseOperation to handle the database operation and error handling
        return $this->handleDatabaseOperation(function () use ($formData) {
            $card_id = $formData['card_id'];
            $card = Card::findBy($card_id, 'id');

            // Update the card's quantity
            $card->quantity($formData['quantity']);

            // Save the updated card in the database
            $card->update();

            // Update the card in the session if it exists
            $this->updateCardInSession($card_id, $formData['quantity']);

            // Return the updated response
            $response = $this->index();
            $response->setMsg('msg=Successfully updated');
            return $response;
        }, $this);
    }

    /**
     * Delete the card with the specified ID.
     *
     * Retrieves the card by its ID and attempts to delete it
     * from the database. Manages redirection and handles any
     * errors during the deletion process.
     *
     * @param int $id The card ID.
     * @return Response
     */
    public function delete(int $id): Response
    {
        // If the card exists, proceed with the deletion process
        return $this->handleDatabaseOperation(function () use ($id) {
            // Retrieve the card record by ID
            $card = Card::findBy($id, 'id');

            // Delete the card from the database
            $card->delete();

            $response = $this->index();
            $response->setMsg('msg=Erfolgreich gelöscht');
        }, $this);
    }

    /**
     * updateCardInSession
     *
     * This method handles the logic of updating the card in the session
     *
     * @param int $card_id
     * @param int $quantity
     * @return void
     */
    private function updateCardInSession(int $card_id, int $quantity): void
    {
        if (isset($_SESSION['card'])) {
            // Loop through session cards to find the card by ID and update its quantity
            foreach ($_SESSION['card'] as &$sessionCard) {
                if ($sessionCard->id() == $card_id) {
                    $sessionCard->quantity($quantity);
                    break;
                }
            }
            // Re-index the session cards array
            $_SESSION['card'] = array_values($_SESSION['card']);
        }
    }
}
