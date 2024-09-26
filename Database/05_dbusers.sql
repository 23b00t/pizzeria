-- Benutzer "reader" erstellen
CREATE USER 'reader'@'localhost' IDENTIFIED BY 'reader_password';

-- Berechtigung für "reader" auf SELECT
GRANT SELECT ON pizzeria.* TO 'reader'@'localhost';

-- Benutzer "writer" erstellen
CREATE USER 'writer'@'localhost' IDENTIFIED BY 'writer_password';

-- Berechtigung für "writer" auf INSERT
GRANT INSERT ON pizzeria.* TO 'writer'@'localhost';

-- Berechtigungen anwenden
FLUSH PRIVILEGES;
