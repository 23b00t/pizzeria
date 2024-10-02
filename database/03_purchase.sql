CREATE TABLE IF NOT EXISTS purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    purchased_at TIMESTAMP,
    delivered_at TIMESTAMP,
    status ENUM('pending', 'placed', 'delivered') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES user(id)
);
