<?php

require_once __DIR__ . '/../helpers/DatabaseHelper.php';

$db = new DatabaseHelper('root', '');

for ($i = 1; $i <= 10; $i++) {
    $email = "user$i@example.com";
    $hashedPassword = password_hash("password$i", PASSWORD_DEFAULT); // Passwort hashen
    $firstName = "FirstName$i";  // Beispiel für Vornamen
    $lastName = "LastName$i";    // Beispiel für Nachnamen
    $street = "StreetName";      // Beispielstraße
    $strNo = $i;                 // Hausnummer
    $zip = 12345 + $i;           // Beispiel-PLZ
    $city = "CityName";          // Beispielstadt

    $db->prepareAndExecute(
        "
        INSERT INTO user (email, hashed_password, first_name, last_name, street, str_no, zip, city, role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'customer')
    ", [$email, $hashedPassword, $firstName, $lastName, $street, $strNo, $zip, $city]
    );
}

// Seed für Pizza
$pizzaNames = ['Margherita', 'Pepperoni', 'Funghi', 'Quattro Stagioni', 'Hawaii'];
foreach ($pizzaNames as $name) {
    $price = rand(8, 15); // Zufälliger Preis zwischen 8 und 15 Euro

    $db->prepareAndExecute(
        "
        INSERT INTO pizza (name, price)
        VALUES (?, ?)
    ", [$name, $price]
    );
}

// Seed für Ingredient
$ingredientNames = ['Tomato', 'Cheese', 'Pepperoni', 'Mushrooms', 'Pineapple'];
foreach ($ingredientNames as $name) {
    $price = rand(0.5, 2);
    $db->prepareAndExecute(
        "
        INSERT INTO ingredient (name, price)
        VALUES (?, ?)
    ", [$name, $price]
    );
}

// Seed für Purchase
for ($i = 1; $i <= 5; $i++) {
    $userId = rand(1, 10); // Zufälliger Benutzer
    $purchasedAt = date('Y-m-d H:i:s');
    $deliveredAt = date('Y-m-d H:i:s', strtotime("+".rand(1, 3)." hours")); // Zufällige Zeit in 1-3 Stunden

    $db->prepareAndExecute(
        "
        INSERT INTO purchase (user_id, purchased_at, delivered_at)
        VALUES (?, ?, ?)
    ", [$userId, $purchasedAt, $deliveredAt]
    );
}

// Seed für Card
for ($i = 1; $i <= 10; $i++) {
    $purchaseId = rand(1, 5);
    $pizzaId = rand(1, count($pizzaNames)); // Zufällige Pizza
    $quantity = rand(1, 5); // Menge zwischen 1 und 5

    $db->prepareAndExecute(
        "
        INSERT INTO card (pizza_id, purchase_id, quantity)
        VALUES (?, ?, ?)
    ", [$pizzaId, $purchaseId, $quantity]
    );
}

// Seed für Pizza_Ingredient
for ($i = 1; $i <= 10; $i++) {
    $pizzaId = rand(1, count($pizzaNames)); // Zufällige Pizza
    $ingredientId = rand(1, count($ingredientNames)); // Zufällige Zutat
    $quantity = rand(1, 2); // Menge zwischen 1 und 2

    $db->prepareAndExecute(
        "
        INSERT INTO pizza_ingredient (pizza_id, ingredient_id, quantity)
        VALUES (?, ?, ?)
    ", [$pizzaId, $ingredientId, $quantity]
    );
}

echo "Seeding completed successfully!\n";
