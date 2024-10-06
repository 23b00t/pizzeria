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
     * @var $purchases Variable is used in the included view.
     */
    public function index(): void
    {
        $purchases = Purchase::findAll(); 

        // Include the view to display all purchases
        include __DIR__ . '/../views/purchase/index.php'; 
    }

    /**
     */
    public function handle($formData): void
    {
        $user_id = $_SESSION['login'];

        if (!isset($_SESSION['purchase_id'])) {
            $purchase = new Purchase($user_id); 

            try {
                // Save the new purchase
                $purchase->save();

                $purchase = Purchase::where('user_id = ? ORDER BY id DESC LIMIT 1', [$user_id])[0];
                $_SESSION['purchase_id'] = $purchase->id();

                // Success: Redirect with a success message
                header('Location: ./index.php?pizza/index?msg=Warenkorb%20hinzugefügt');
                exit();
            } catch (PDOException $e) {
                // Log the error message
                error_log($e->getMessage());
                // Error handling: Redirect with an error message
                header('Location: ./index.php?pizza/index?error=Konnte%20Warenkorb%20nicht%20hinzugefügt%20werden');
                exit();
            }
        } 

        $purchase_id = $_SESSION['purchase_id'];
        // Create the card entry
        $card = new Card($formData['pizza_id'], $purchase_id, $formData['quantity']);
        $card->save();
        if (!isset($_SESSION['card'])) {
            $_SESSION['card'] = [];
        }
        $_SESSION['card'][] = serialize($card);
        // Success: Redirect with a success message
        header('Location: ./index.php?pizza/index?msg=Warenkorb%20hinzugefügt');
        exit();
    }

    /**
     * Handle the update process for an existing purchase.
     *
     * This method retrieves the purchase by its ID, validates the provided
     * form data, updates the purchase's properties, and saves the changes
     * to the database. It also manages redirection upon success or failure.
     *
     * @param int   $id       The purchase ID to update.
     * @param array $formData The form data submitted for updating the purchase.
     */
    public function update($id, $formData): void
    {
        $purchase = Purchase::findBy($id, 'id');

        if ($purchase) {
            // Update the purchase properties
            $purchase->status($formData['status']);
           
            // $purchase->delivered_at()

            try {
                // Save the updated purchase to the database
                $purchase->update(); 
                header('Location: ./index.php?purchase/show/' . $id . '?msg=Purchase%20successfully%20updated');
                exit();
            } catch (PDOException $e) {
                header('Location: ./index.php?purchase/show/' . $id . '?msg=Error');
                exit();
            }
        } 
    }

    /**
     * Delete the purchase with the specified ID.
     *
     * This method retrieves the purchase by its ID and attempts to delete it
     * from the database. It manages the redirection and handles any errors
     * that may occur during the deletion process.
     *
     * @param int $id The purchase ID.
     */
    public function delete($id): void
    {
        $purchase = Purchase::findBy($id, 'id');

        if ($purchase) {
            try {
                $purchase->delete();
                header('Location: ./index.php?purchase/index?msg=Purchase%20successfully%20deleted');
                exit();
            } catch (PDOException $e) {
                error_log($e->getMessage());
                // Handle errors as needed
                header('Location: ./index.php?purchase/index?msg=error');
                exit();
            }
        }
    }
}
