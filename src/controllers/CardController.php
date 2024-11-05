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
     * Show detailed information about a specific card by purchase ID.
     *
     * Retrieves the purchase record by its ID and any associated cards,
     * then includes the card detail view to display this information.
     *
     * @param int $id The purchase ID.
     *
     * @return array
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

        $this->view = 'card/show';
        return [ 'purchase' => $purchase, 'cards' => $cards ];
    }

    /**
     * Show detailed information about the pending card of the current user.
     *
     * Checks if the user has a pending card in the session and displays
     * its details. If the associated purchase has been delivered, the
     * session is cleared.
     * @return array
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

        $this->view = 'card/show';
        return [ 'purchase' => $purchase, 'cards' => $cards ];
    }

    /**
     * Handle the update process for an existing card.
     *
     * Retrieves the card by its ID, updates its quantity based on the
     * provided form data, and saves the changes to the database.
     * It also updates the session if necessary.
     *
     * @param array $formData The form data submitted for updating the card.
     * @return void
     */
    public function update(array $formData): void
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

                $this->setRedirect();
                $this->action = 'showOpenCard';
                $this->msg = 'msg=Erfolgreich aktualisiert';
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                $this->setRedirect();
                $this->action = 'showOpenCard';
                $this->msg = 'error=Fehler';
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
     * @return void
     */
    public function delete(int $id): void
    {
        // Retrieve the card record by ID
        $card = Card::findBy($id, 'id');

        // If the card exists, proceed with the deletion process
        if ($card) {
            try {
                // Delete the card from the database
                $card->delete();

                $this->setRedirect();
                $this->action = 'showOpenCard';
                $this->msg = 'msg=Erfolgreich gelöscht';
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                $this->setRedirect();
                $this->action = 'showOpenCard';
                $this->msg = 'error=Fehler';
            }
        }
    }

    /**
     * @return void
     */
    private function setRedirect(): void
    {
        $this->redirect = true;
        $this->area = 'card';
    }
}
