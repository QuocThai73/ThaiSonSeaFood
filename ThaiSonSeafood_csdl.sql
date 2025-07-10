CREATE DATABASE IF NOT EXISTS ThaiSonSeafood;
USE ThaiSonSeafood;

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS Users (
    user_id VARCHAR(10) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS Products (
    product_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    quantity INT,
    img VARCHAR(255) DEFAULT NULL,
    category VARCHAR(50) DEFAULT NULL, -- thêm dòng này
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng sản phẩm đã lưu
CREATE TABLE IF NOT EXISTS SavedItems (
    saved_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10),
    product_id VARCHAR(10),
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(10),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    address VARCHAR(255),
    phone VARCHAR(20),
    method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS OrderDetails (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id VARCHAR(10),
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Dữ liệu mẫu cho Users
INSERT INTO Users (user_id, username, password, email)
VALUES
    ('U001', 'admin', 'admin', 'admin@gmail.com'),
    ('U002', 'nguoimua2', 'hashed_password_2', 'user2@example.com');

-- Dữ liệu mẫu cho Products (có trường img)
INSERT INTO Products (product_id, name, description, price, quantity, img, category)
VALUES
    ('P001', 'Cá Hồi Na Uy', 'Cá hồi tươi ngon nhập khẩu từ Na Uy', 250000, 50, 'product_images/cahoi.jpg', 'Cá'),
    ('P002', 'Tôm Sú Cà Mau', 'Tôm sú loại lớn từ vùng biển Cà Mau', 180000, 100, 'product_images/tomsu.jpg', 'Tôm'),
    ('P003', 'Mực Ống', 'Mực ống tươi sống đánh bắt từ biển miền Trung', 200000, 80, 'product_images/mucuong.jpg', 'Mực');

-- Dữ liệu mẫu cho Orders
INSERT INTO Orders (user_id, total, address, phone, method)
VALUES
    ('U001', 430000, '123 Đường ABC, Quận 1', '0901234567', 'tới lấy'),
    ('U002', 380000, '456 Đường XYZ, Quận 2', '0912345678', 'vận chuyển');

-- Dữ liệu mẫu cho OrderDetails
INSERT INTO OrderDetails (order_id, product_id, quantity, price)
VALUES
    (1, 'P001', 1, 250000),
    (1, 'P002', 1, 180000),
    (2, 'P002', 2, 360000),
    (2, 'P003', 1, 200000);

-- Dữ liệu mẫu cho SavedItems
INSERT INTO SavedItems (user_id, product_id)
VALUES
    ('U001', 'P003'),
    ('U002', 'P001');