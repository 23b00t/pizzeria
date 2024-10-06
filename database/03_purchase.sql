CREATE TABLE IF NOT EXISTS purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    purchased_at TIMESTAMP DEFAULT NULL,
    delivered_at TIMESTAMP DEFAULT NULL,
    status ENUM('pending', 'placed', 'delivered') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES user(id)
);

DELIMITER //

CREATE TRIGGER update_timestamps BEFORE UPDATE ON purchase
FOR EACH ROW
BEGIN
    -- Wenn der Status auf 'placed' gesetzt wird
    IF NEW.status = 'placed' AND OLD.status != 'placed' THEN
        SET NEW.purchased_at = CURRENT_TIMESTAMP;
    END IF;

    -- Wenn der Status auf 'delivered' gesetzt wird
    IF NEW.status = 'delivered' AND OLD.status != 'delivered' THEN
        SET NEW.delivered_at = CURRENT_TIMESTAMP;
    END IF;
END//

DELIMITER ;
