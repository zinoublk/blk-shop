-- Database: blkshop
CREATE DATABASE IF NOT EXISTS blkshop;
USE blkshop;

-- Table: products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DOUBLE NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Table: admins
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Ensure default admin exists and has correct password
INSERT INTO admins (username, password) VALUES ('blkadmin', '123456')
ON DUPLICATE KEY UPDATE password = '123456';

-- Sample Products (Optional but good for testing)
INSERT IGNORE INTO products (name, price, image) VALUES
('Samsung Galaxy S24', 900, 'samsung.jpg'),
('iPhone 15 Pro', 1200, 'apple.jpg'),
('Oppo Find X5', 600, 'oppo.jpg');
