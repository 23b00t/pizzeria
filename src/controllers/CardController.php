<?php

namespace app\controllers;

use app\models\User;
use app\models\Card;
use app\models\Purchase;
use PDOException;

/**
 * CardController class responsible for managing card-related actions,
 * such as displaying card details, handling card updates, and deletions.
 */
class CardController
{
    /**
     * Show detailed information about a specific card by purchase ID.
     *
     * Retrieves the purchase record by its ID and any associated cards,
     * then includes the card detail view to display this information.
     *
     * @param int $id The purchase ID.
     *
     * @return void
     */
    public function show(int $id): array
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

        // Include the card detail view, passing the card object for rendering
        return ['view' => 'card/show', 'purchase' => $purchase, 'cards' => $cards];
    }

    /**
     * Show detailed information about the pending card of the current user.
     *
     * Checks if the user has a pending card in the session and displays
     * its details. If the associated purchase has been delivered, the
     * session is cleared.
     * @return array<string,string>
     */
    public function showOpenCard(): array
    {
        // Retrieve the card and purchase ID from the session
        $cards = $_SESSION['card'] ?? [];
        $purchase_id = $_SESSION['purchase_id'] ?? 0;

        // Retrieve the purchase record using the session's purchase ID
        $purchase = Purchase::findBy($purchase_id, 'id');

        // If the purchase is delivered, clear the session and reset variables
        if (isset($purchase) && $purchase->status() === 'delivered') {
            unset($_SESSION['purchase_id']);
            unset($_SESSION['card']);
            $purchase = null;
            $cards = [];
        }

        // Include the card detail view and pass the card object
        return ['view' => 'card/show', 'purchase' => $purchase, 'cards' => $cards];
    }

    /**
     * Handle the update process for an existing card.
     *
     * Retrieves the card by its ID, updates its quantity based on the
     * provided form data, and saves the changes to the database.
     * It also updates the session if necessary.
     *
     * @param array $formData The form data submitted for updating the card.
     * @return array<string,string>
     */
    public function update(array $formData): array
    {
        // Get the card ID from the form data
        $card_id = $formData['card_id'];

        // Retrieve the card record by ID
        $card = Card::findBy($card_id, 'id');

        // If the card exists, proceed with the update process
        if ($card) {
            // Update the card's quantity using the form data
            $card->quantity($formData['quantity']);

            try {
                // Save the updated card to the database
                $card->update();

                // Update the card in the session if it exists
                if (isset($_SESSION['card'])) {
                    foreach ($_SESSION['card'] as &$sessionCard) {
                        if ($sessionCard->id() == $card_id) {
                            // Update the session object directly with the new quantity
                            $sessionCard->quantity($formData['quantity']);
                            break;
                        }
                    }
                    // Reassign the session with the updated card data
                    $_SESSION['card'] = array_values($_SESSION['card']);
                }

                return ['redirect' => 'true', 'area' => 'card', 'action' => 'showOpenCard', 'msg' => 'Erfolgreich'];
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                return ['redirect' => 'true', 'area' => 'card', 'action' => 'showOpenCard', 'msg' => 'Fehler'];
            }
        }
    }

    /**
     * Delete the card with the specified ID.
     *
     * Retrieves the card by its ID and attempts to delete it
     * from the database. Manages redirection and handles any
     * errors during the deletion process.
     *
     * @param int $id The card ID.
     * @return array<string,string>
     */
    public function delete(int $id): array
    {
        // Retrieve the card record by ID
        $card = Card::findBy($id, 'id');

        // If the card exists, proceed with the deletion process
        if ($card) {
            try {
                // Delete the card from the database
                $card->delete();

                // Redirect with a success message after deletion
                return ['redirect' => 'true', 'area' => 'card', 'action' => 'index', 'msg' => 'Erfolgreich gelöscht'];
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                return ['redirect' => 'true', 'area' => 'card', 'action' => 'index', 'msg' => 'Fehler'];
            }
        }
    }
}
