-- Create the database
CREATE DATABASE smart_laundry;

-- Use the database
USE smart_laundry;

-- Create users table
DROP TABLE IF EXISTS `users`; 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role BOOLEAN DEFAULT 0, -- 1 = admin, 0 = normal user
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM `users` WHERE 1

