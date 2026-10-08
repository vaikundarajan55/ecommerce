-- Website content update: editable About / Contact pages, testimonials,
-- "current" and "peak" product flags, and one Enquiries inbox for product + contact messages.
-- Run once on an existing ecommerce_ci4 database (fresh installs get this from ecommerce_ci4.sql).

USE ecommerce_ci4;

CREATE TABLE IF NOT EXISTS settings (
  skey VARCHAR(60) PRIMARY KEY,
  svalue TEXT NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS testimonials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  role VARCHAR(120) NULL,
  message TEXT NOT NULL,
  rating TINYINT NOT NULL DEFAULT 5,
  photo VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB;

ALTER TABLE products
  ADD COLUMN is_current TINYINT(1) NOT NULL DEFAULT 0 AFTER featured,
  ADD COLUMN is_peak TINYINT(1) NOT NULL DEFAULT 0 AFTER is_current;

ALTER TABLE enquiries
  ADD COLUMN source ENUM('product','contact') NOT NULL DEFAULT 'product' AFTER product_id,
  ADD COLUMN subject VARCHAR(200) NULL AFTER phone;

-- Move any contact-form messages into the enquiries inbox, then drop the old table
INSERT INTO enquiries (product_id, source, name, email, phone, subject, message, is_read, created_at)
  SELECT NULL, 'contact', name, email, NULL, subject, message, is_read, created_at FROM contacts;
DROP TABLE contacts;

INSERT IGNORE INTO settings (skey, svalue, updated_at) VALUES
('about_tagline', 'A small team selling things we would use ourselves.', NOW()),
('about_heading', 'Started in Puducherry, shipping across India', NOW()),
('about_body', 'We began as a two-person shop selling home and kitchen items to neighbours. Today we ship electronics, fashion, home and beauty products to customers across the country.\n\nEvery product listed here is checked by our team before it goes live. If something is not right, you can return it within 7 days.', NOW()),
('about_stats', '[{"value":"10k+","label":"Orders delivered"},{"value":"500+","label":"Products"},{"value":"4.7/5","label":"Average rating"},{"value":"48 hrs","label":"Typical dispatch time"}]', NOW()),
('about_promises', '[{"title":"Genuine products","text":"Sourced from brands and verified suppliers."},{"title":"Careful packing","text":"Fragile items are double-wrapped."},{"title":"Human support","text":"Write to us and a real person answers."}]', NOW()),
('contact_tagline', 'Send us a message and we will reply within one working day.', NOW()),
('contact_address', '12 Beach Road, Puducherry 605001', NOW()),
('contact_phone', '+91 98765 43210', NOW()),
('contact_email', 'support@shopkart.test', NOW()),
('contact_hours', 'Mon–Sat, 9am – 7pm', NOW()),
('contact_map', '', NOW());
