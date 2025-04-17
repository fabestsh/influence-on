-- Active: 1741594309462@@127.0.0.1@3306@influenceon
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('business', 'influencer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
