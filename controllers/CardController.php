<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';
require_once __DIR__ . '/../models/Card.php';
require_once __DIR__ . '/../models/Card.php';

/**
 * CardController class responsible for managing card-related actions,
 * such as displaying card details, handling card creation, updating,
 * and deletion.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all cards available in the system.
 * - show(int $id): void: Displays detailed information about a specific card based on the given ID.
 * - create(): void: Renders the form for creating a new card.
 * - store(array $formData): void: Validates the provided form data and saves a new card to the database.
 * - edit(int $id): void: Retrieves the specified card by ID and renders the edit form for that card.
 * - update(int $id, array $formData): void: Validates the provided form data and updates the card with the given ID.
 * - delete(int $id): void: Deletes the card identified by the specified ID from the database.
 */
class CardController
{
    /**
     * Show detailed information about a specific card.
     *
     * This method retrieves the card by its purchase ID and any associated 
     * then includes the card detail view to display the information.
     *
     * @param int $id The purchase ID.
     */
    public function show($id): void
    {
        $purchase = Purchase::findBy($id, 'id');
        $allCards = Card::findAll();
        $cards = array_filter($allCards, function($c) use ($id) {
            // compare string with int 
            return $c->purchase_id() == $id;
        });

        // Include the card detail view and pass the card object
        include './views/card/show.php'; 
    }

    /**
     * Show detailed information about the pending card
     *
     * This method retrieves the card by its ID and any associated ingredients,
     * then includes the card detail view to display the information.
     */
    public function showOpenCard(): void
    {
        $cards = $_SESSION['card'];
        $purchase = Purchase::findBy($_SESSION['purchase_id'], 'id');
        // Include the card detail view and pass the card object
        include './views/card/show.php'; 
    }

    /**
     * Handle the kupdate process for an existing card.
     *
     * This method retrieves the card by its ID, validates the provided
     * form data, updates the card's properties, and saves the changes
     * to the database. It also manages redirection upon success or failure.
     *
     * @param int   $id       The card ID to update.
     * @param array $formData The form data submitted for updating the card.
     */
    public function update($id, $formData): void
    {
        $card = Card::findBy($id, 'id');

        if ($card) {
            // Update the card properties
            $card->quantity($formData['quantity']);
            $purchase_id = $card->purchase_id();           

            try {
                // Save the updated card to the database
                $card->update(); 
                header('Location: ./index.php?card/show/' . $purchase_id . '?msg=Card%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                header('Location: ./index.php?card/show/' . $purchase_id . '?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Delete the card with the specified ID.
     *
     * This method retrieves the card by its ID and attempts to delete it
     * from the database. It manages the redirection and handles any errors
     * that may occur during the deletion process.
     *
     * @param int $id The card ID.
     */
    public function delete($id): void
    {
        $card = Card::findBy($id, 'id');

        if ($card) {
            try {
                $card->delete();
                header('Location: ./index.php?card/show/' . $card->purchase_id() . '?msg=Card%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                error_log($e->getMessage());
                // Handle errors as needed
                header('Location: ./index.php?card/index?msg=error');
                exit();
            }
        }
    }
}
