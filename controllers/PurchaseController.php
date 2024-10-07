<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';
require_once __DIR__ . '/../helpers/FormCheckHelper.php';
require_once __DIR__ . '/../models/Purchase.php';
require_once __DIR__ . '/../models/Card.php';

/**
 * PurchaseController class responsible for managing purchase-related actions,
 * such as displaying purchase details, handling purchase creation, updating,
 * and deletion.
 * 
 * Methods:
 * 
 * - index(): void: Displays a list of all purchases available in the system.
 * - show(int $id): void: Displays detailed information about a specific purchase based on the given ID.
 * - create(): void: Renders the form for creating a new purchase.
 * - store(array $formData): void: Validates the provided form data and saves a new purchase to the database.
 * - edit(int $id): void: Retrieves the specified purchase by ID and renders the edit form for that purchase.
 * - update(int $id, array $formData): void: Validates the provided form data and updates the purchase with the given ID.
 * - delete(int $id): void: Deletes the purchase identified by the specified ID from the database.
 */
class PurchaseController
{
    /**
     * Display a list of all purchases.
     *
     * This method fetches all purchases from the database and includes
     * a view to display them. The $purchases variable is passed to the view
     * for rendering.
     *
     * @var $purchases Variable is used in the included view.
     */
    public function index(): void
    {
        // Retrieve all purchases from the database
        $purchases = Purchase::findAll(); 

        // Include the view to display all purchases
        include __DIR__ . '/../views/purchase/index.php'; 
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
     */
    public function handle(array $formData): void
    {
        // Get the current user's ID from the session
        $user_id = $_SESSION['login'];

        // Check if there's already an active purchase in the session
        if (!isset($_SESSION['purchase_id'])) {
            // Create a new purchase for the user
            $purchase = new Purchase($user_id); 

            try {
                // Save the new purchase to the database
                $purchase->save();

                // Retrieve the latest purchase created by this user and store its ID in the session
                $purchase = Purchase::where('user_id = ? ORDER BY id DESC LIMIT 1', [$user_id])[0];
                $_SESSION['purchase_id'] = $purchase->id();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?pizza/index?error=Konnte%20Warenkorb%20nicht%20hinzugefügt%20werden');
                exit();
            }
        } 

        // Retrieve the purchase ID from the session
        $purchase_id = $_SESSION['purchase_id'];
        
        // Create a new card (pizza order) associated with the purchase
        $card = new Card($formData['pizza_id'], $purchase_id, $formData['quantity']);
        $card->save();

        // Retrieve the latest card associated with this purchase and store it in the session
        $card = Card::where('purchase_id = ? ORDER BY id DESC LIMIT 1', [$purchase_id])[0];        
        $_SESSION['card'][] = $card;

        // Redirect with a success message after adding the item to the cart
        header('Location: ./index.php?pizza/index?msg=Warenkorb%20hinzugefügt');
        exit();
    }

    /**
     * Handle the order process for an existing purchase.
     *
     * This method sets the status of the purchase to "placed" and saves it.
     * It manages the redirection and handles any errors that may occur.
     *
     * @param int $id The purchase ID to place the order for.
     */
    public function place(string $id): void
    {
        // Retrieve the purchase record by ID
        $purchase = Purchase::findBy($id, 'id');

        // If the purchase exists, update its status to "placed"
        if ($purchase) {
            $purchase->status('placed');
           
            try {
                // Save the updated purchase to the database
                $purchase->update(); 

                // Redirect with a success message
                header('Location: ./index.php?card/card?msg=Purchase%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?card/card?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Handle the update process for an existing purchase.
     *
     * This method updates the status of the purchase to "delivered" and saves
     * the changes to the database. It also manages redirection upon success or failure.
     *
     * @param int $id The purchase ID to update.
     */
    public function update(string $id): void
    {
        // Retrieve the purchase record by ID
        $purchase = Purchase::findBy($id, 'id');

        // If the purchase exists, update its status to "delivered"
        if ($purchase) {
            $purchase->status('delivered');
           
            try {
                // Save the updated purchase to the database
                $purchase->update(); 

                // Redirect with a success message
                header('Location: ./index.php?purchase/index?msg=Purchase%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?purchase/index?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Delete the purchase with the specified ID.
     *
     * This method retrieves the purchase by its ID and attempts to delete it
     * from the database. If successful, it clears any related session data
     * and redirects to the index with a success message.
     *
     * @param int $id The purchase ID.
     */
    public function delete(string $id): void
    {
        // Retrieve the purchase record by ID
        $purchase = Purchase::findBy($id, 'id');

        // If the purchase exists, proceed with the deletion process
        if ($purchase) {
            try {
                // Delete the purchase from the database
                $purchase->delete();

                // Clear session data related to the purchase
                unset($_SESSION['card']);
                unset($_SESSION['purchase_id']);

                // Redirect with a success message after deletion
                header('Location: ./index.php?purchase/index?msg=Purchase%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                // Log the error and redirect with an error message
                error_log($e->getMessage());
                header('Location: ./index.php?purchase/index?msg=error');
                exit();
            }
        }
    }
}
