-- Create user "reader" if it does not exist
CREATE USER 'reader'@'localhost' IDENTIFIED BY 'reader_password';

-- Grant SELECT permission on all tables in pizzeria to "reader"
GRANT SELECT ON pizzeria.* TO 'reader'@'localhost';

-- Create user "writer" if it does not exist
CREATE USER 'writer'@'localhost' IDENTIFIED BY 'writer_password';

-- Grant INSERT permission on all tables in pizzeria to "writer"
GRANT INSERT ON pizzeria.* TO 'writer'@'localhost';

-- Apply the changes to privileges
FLUSH PRIVILEGES;
