CREATE DATABASE IF NOT EXISTS ThaiSonSeafood;
USE ThaiSonSeafood;

CREATE TABLE IF NOT EXISTS Users (
    user_id VARCHAR(10) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Products (
    product_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    quantity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS SavedItems (
    saved_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10),
    product_id VARCHAR(10),
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

CREATE TABLE IF NOT EXISTS Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE IF NOT EXISTS OrderDetails (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id VARCHAR(10),
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Insert sample users
INSERT INTO Users (user_id, username, password, email) VALUES
('U001', 'nguoimua1', 'hashed_password_1', 'user1@example.com'),
('U002', 'nguoimua2', 'hashed_password_2', 'user2@example.com');

-- Insert sample products
INSERT INTO Products (product_id, name, description, price, quantity) VALUES
('P001', 'Cá Hồi Na Uy', 'Cá hồi tươi ngon nhập khẩu từ Na Uy', 250000, 50),
('P002', 'Tôm Sú Cà Mau', 'Tôm sú loại lớn từ vùng biển Cà Mau', 180000, 100),
('P003', 'Mực Ống', 'Mực ống tươi sống đánh bắt từ biển miền Trung', 200000, 80);

-- Insert sample orders
INSERT INTO Orders (user_id, total) VALUES
('U001', 430000),
('U002', 380000);

-- Insert order details
INSERT INTO OrderDetails (order_id, product_id, quantity, price) VALUES
(1, 'P001', 1, 250000),
(1, 'P002', 1, 180000),
(2, 'P002', 2, 360000),
(2, 'P003', 1, 200000);

-- Insert saved items
INSERT INTO SavedItems (user_id, product_id) VALUES
('U001', 'P003'),
('U002', 'P001');
