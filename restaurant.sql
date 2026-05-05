-- Restaurant Database
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  icon VARCHAR(10) NOT NULL
);

CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(255),
  is_available TINYINT(1) DEFAULT 1,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(150) NOT NULL,
  customer_phone VARCHAR(20),
  table_number INT,
  total_amount DECIMAL(10,2),
  status ENUM('pending','preparing','served','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  menu_item_id INT,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Insert Categories
INSERT INTO categories (name, icon) VALUES
('Starters', '🥗'),
('Main Course', '🍛'),
('Burgers', '🍔'),
('Pizza', '🍕'),
('Drinks', '🥤'),
('Desserts', '🍰');

-- Insert Menu Items
INSERT INTO menu_items (category_id, name, description, price, image_url) VALUES
(1, 'Veg Spring Rolls', 'Crispy rolls with mixed vegetables and dipping sauce', 120.00, 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400'),
(1, 'Chicken Tikka', 'Marinated chicken grilled to perfection', 220.00, 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400'),
(1, 'Paneer Tikka', 'Spiced cottage cheese grilled with peppers', 180.00, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d6?w=400'),
(2, 'Butter Chicken', 'Classic creamy tomato-based chicken curry', 320.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400'),
(2, 'Dal Makhani', 'Slow cooked black lentils in rich gravy', 240.00, 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400'),
(2, 'Biryani', 'Fragrant basmati rice with spiced chicken', 350.00, 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=400'),
(3, 'Classic Beef Burger', 'Juicy beef patty with lettuce, tomato and cheese', 280.00, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400'),
(3, 'Chicken Burger', 'Crispy fried chicken with spicy mayo', 260.00, 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=400'),
(4, 'Margherita Pizza', 'Fresh tomato, mozzarella and basil', 299.00, 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400'),
(4, 'Pepperoni Pizza', 'Loaded with pepperoni and cheese', 349.00, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400'),
(5, 'Fresh Lime Soda', 'Refreshing lime with soda', 80.00, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400'),
(5, 'Mango Lassi', 'Creamy mango yogurt drink', 120.00, 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400'),
(6, 'Gulab Jamun', 'Soft milk-solid dumplings in sugar syrup', 100.00, 'https://images.unsplash.com/photo-1666589188739-c4b14c5c6f19?w=400'),
(6, 'Chocolate Lava Cake', 'Warm chocolate cake with molten center', 180.00, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400');
