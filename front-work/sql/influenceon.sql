-- Create the database
CREATE DATABASE IF NOT EXISTS influenceon
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE influenceon;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('business', 'influencer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- Businesses table
CREATE TABLE IF NOT EXISTS businesses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    industry VARCHAR(100) NOT NULL,
    website VARCHAR(255),
    contact_info VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_industry (industry)
) ENGINE=InnoDB;

-- Influencers table
CREATE TABLE IF NOT EXISTS influencers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    social_links JSON NOT NULL,
    expertise JSON NOT NULL,
    age INT,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (JSON_VALID(social_links)),
    CHECK (JSON_VALID(expertise))
) ENGINE=InnoDB; 