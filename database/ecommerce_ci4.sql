-- Ecommerce CI4 database  |  import via phpMyAdmin or: mysql -u root < ecommerce_ci4.sql
CREATE DATABASE IF NOT EXISTS ecommerce_ci4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_ci4;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_items, orders, enquiries, contacts, products, subcategories, categories, banners, users, admins;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) NULL,
  password VARCHAR(255) NOT NULL,
  address VARCHAR(255) NULL,
  city VARCHAR(100) NULL,
  pincode VARCHAR(12) NULL,
  reset_token VARCHAR(100) NULL,
  reset_expires DATETIME NULL,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE banners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  subtitle VARCHAR(255) NULL,
  image VARCHAR(255) NULL,
  link VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  image VARCHAR(255) NULL,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE subcategories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  subcategory_id INT UNSIGNED NULL,
  name VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  sku VARCHAR(60) NULL,
  short_desc VARCHAR(300) NULL,
  description TEXT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  sale_price DECIMAL(10,2) NULL,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255) NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_no VARCHAR(30) NOT NULL UNIQUE,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  pincode VARCHAR(12) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
  shipping DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  payment_method VARCHAR(30) NOT NULL DEFAULT 'dummy_gateway',
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  txn_id VARCHAR(60) NULL,
  status ENUM('placed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'placed',
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  name VARCHAR(200) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  qty INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE enquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id INT UNSIGNED NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE contacts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200) NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NULL
) ENGINE=InnoDB;

-- ---------------- Sample data ----------------
-- Admin login:  admin@example.com / admin123
INSERT INTO admins (name,email,password,created_at) VALUES ('Store Admin','admin@example.com','$2y$10$wkNzzjjgymZiQTSci./3eOkPaZMNa91AS6WGg8DiAZcXVfY1DiPZu',NOW());
-- Customer login: user@example.com / user123
INSERT INTO users (name,email,phone,password,address,city,pincode,created_at) VALUES
('Demo Customer','user@example.com','9876543210','$2y$10$uc8bTl/sRYQs4yPsJO0rxu0bGgQBfTbhtZot6TJTpT0q87pUd.yhG','12 Beach Road','Puducherry','605001',NOW());

INSERT INTO banners (title,subtitle,link,sort_order,status,created_at) VALUES
('Everyday essentials, delivered','Fresh arrivals across home, fashion and gadgets','/shop',1,1,NOW()),
('Festive season sale','Up to 40% off on selected products','/shop',2,1,NOW()),
('New gadgets are in','Headphones, watches and more','/shop?cat=electronics',3,1,NOW());

INSERT INTO categories (name,slug,status,created_at) VALUES
('Electronics','electronics',1,NOW()),('Fashion','fashion',1,NOW()),('Home & Kitchen','home-kitchen',1,NOW()),('Beauty','beauty',1,NOW());

INSERT INTO subcategories (category_id,name,slug,status,created_at) VALUES
(1,'Audio','audio',1,NOW()),(1,'Wearables','wearables',1,NOW()),
(2,'Men','men',1,NOW()),(2,'Women','women',1,NOW()),
(3,'Cookware','cookware',1,NOW()),(3,'Decor','decor',1,NOW()),
(4,'Skincare','skincare',1,NOW());

INSERT INTO products (category_id,subcategory_id,name,slug,sku,short_desc,description,price,sale_price,stock,featured,status,created_at) VALUES
(1,1,'Wireless Headphones','wireless-headphones','EL-001','40-hour battery, deep bass, foldable design.','Over-ear wireless headphones with active noise reduction, soft ear cushions and a 40-hour battery. Comes with a carry pouch and a charging cable.',2999,1999,25,1,1,NOW()),
(1,1,'Bluetooth Speaker','bluetooth-speaker','EL-002','Splash-proof speaker with 12 hours of playtime.','Portable speaker with 360 degree sound, IPX5 splash protection and a built-in mic for calls.',1799,1299,40,1,1,NOW()),
(1,2,'Smart Watch Pro','smart-watch-pro','EL-003','Heart rate, sleep tracking and 7-day battery.','AMOLED smart watch with heart-rate, SpO2 and sleep tracking, 100+ watch faces and 7-day battery life.',4999,3499,15,1,1,NOW()),
(2,3,'Cotton Casual Shirt','cotton-casual-shirt','FA-001','Breathable 100% cotton, regular fit.','A everyday shirt in soft washed cotton. Regular fit, available in multiple sizes.',1299,899,60,0,1,NOW()),
(2,4,'Printed Kurti','printed-kurti','FA-002','Light rayon kurti with block print.','Comfortable rayon kurti with a hand block-inspired print and three-quarter sleeves.',1099,NULL,50,1,1,NOW()),
(3,5,'Non-stick Cookware Set','nonstick-cookware-set','HK-001','3-piece set: kadai, tawa and fry pan.','Induction-friendly non-stick cookware set with cool-touch handles.',2499,1899,20,1,1,NOW()),
(3,6,'Ceramic Table Vase','ceramic-table-vase','HK-002','Hand-finished ceramic vase, 25 cm.','Minimal ceramic vase with a matte glaze. Suits fresh and dried flowers.',799,NULL,35,0,1,NOW()),
(4,7,'Vitamin C Face Serum','vitamin-c-face-serum','BT-001','30 ml brightening serum for daily use.','Lightweight serum with 10% vitamin C and hyaluronic acid. Dermatologist tested.',699,549,80,1,1,NOW());
