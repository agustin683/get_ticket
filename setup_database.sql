CREATE DATABASE ticket_system;

USE ticket_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    department VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    file VARCHAR(100),
    status VARCHAR(20) DEFAULT 'pending',
    progress VARCHAR(20) DEFAULT '0%',
    assigned_to INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT,
    user_id INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Crear usuarios administradores
INSERT INTO users (username, password, role) VALUES ('admin1', 'password1', 'admin');
INSERT INTO users (username, password, role) VALUES ('admin2', 'password2', 'admin');

-- Crear usuarios especialistas
INSERT INTO users (username, password, role) VALUES ('specialist1', 'password1', 'specialist');
INSERT INTO users (username, password, role) VALUES ('specialist2', 'password2', 'specialist');
INSERT INTO users (username, password, role) VALUES ('specialist3', 'password3', 'specialist');
INSERT INTO users (username, password, role) VALUES ('specialist4', 'password4', 'specialist');
INSERT INTO users (username, password, role) VALUES ('specialist5', 'password5', 'specialist');