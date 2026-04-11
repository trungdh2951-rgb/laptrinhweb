CREATE DATABASE IF NOT EXISTS web_ban_hang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE web_ban_hang;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    phone VARCHAR(20),
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM products;
DELETE FROM categories;

INSERT INTO categories (id, name, description) VALUES
(1, 'Điện thoại', 'Các loại điện thoại thông minh mới nhất'),
(2, 'Laptop', 'Laptop học tập, văn phòng và gaming'),
(3, 'Phụ kiện', 'Tai nghe, chuột, bàn phím, sạc dự phòng')
ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description);

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'iPhone 13', 'Điện thoại Apple iPhone 13, thiết kế sang trọng, hiệu năng ổn định.', 15990000, 10, 'iphone13.jpg'),
(1, 'Samsung Galaxy S23', 'Điện thoại Samsung cao cấp với màn hình đẹp và camera tốt.', 18990000, 8, 's23.jpg'),
(2, 'MacBook Air M2', 'Laptop mỏng nhẹ, pin lâu, phù hợp học tập và làm việc.', 28990000, 5, 'macbookairm2.jpg'),
(3, 'Tai nghe Bluetooth', 'Tai nghe không dây tiện lợi, âm thanh rõ ràng.', 990000, 20, 'tainghe.jpg');

INSERT INTO users (full_name, email, password, phone, address, role)
SELECT 'Administrator', 'admin@gmail.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '0123456789', 'TP.HCM', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@gmail.com');
