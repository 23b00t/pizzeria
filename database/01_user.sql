CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255),
    last_name VARCHAR(255) NOT NULL,
    street VARCHAR(255) NOT NULL,
    str_no INT NOT NULL,
    zip INT NOT NULL,
    city VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'customer'
);
