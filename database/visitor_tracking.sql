-- Visitor tracking: one row per visitor IP per day, plus the buyer's IP on every order.
-- Run once on an existing ecommerce_ci4 database (fresh installs get this from ecommerce_ci4.sql).

USE ecommerce_ci4;

CREATE TABLE IF NOT EXISTS visitors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ip_address VARCHAR(45) NOT NULL,
  visit_date DATE NOT NULL,
  user_id INT UNSIGNED NULL,
  user_agent VARCHAR(255) NULL,
  last_page VARCHAR(255) NULL,
  hits INT UNSIGNED NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  UNIQUE KEY ip_day (ip_address, visit_date),
  KEY visit_date (visit_date)
) ENGINE=InnoDB;

-- Every page a visitor opened (shown in the admin "views" popup)
CREATE TABLE IF NOT EXISTS visitor_page_views (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitor_id INT UNSIGNED NOT NULL,
  page VARCHAR(255) NOT NULL,
  viewed_at DATETIME NOT NULL,
  KEY visitor_id (visitor_id),
  FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE orders ADD COLUMN ip_address VARCHAR(45) NULL AFTER txn_id;
