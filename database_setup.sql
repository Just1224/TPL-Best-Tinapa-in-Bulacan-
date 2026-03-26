

-- PostgreSQL compatible schema for PTL Best Tinapa CMS

-- Create database (run this separately if needed)
-- CREATE DATABASE tinapa_cms;

-- Connect to database
-- \c tinapa_cms;

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site content table
CREATE TABLE IF NOT EXISTS site_content (
    id SERIAL PRIMARY KEY,
    section VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(200),
    content TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS messages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE
);

-- Users table (for customer accounts)
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shopping cart table
CREATE TABLE IF NOT EXISTS cart (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    quantity INTEGER DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE (user_id, service_id)
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INTEGER NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status VARCHAR(50) DEFAULT 'pending',
    order_status VARCHAR(50) DEFAULT 'pending',
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    delivery_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table (individual products in an order)
CREATE TABLE IF NOT EXISTS order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    product_name VARCHAR(200),
    quantity INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

-- Insert sample admin user
INSERT INTO admin (name, email, password, role) VALUES
('Admin User', 'admin@tinapa.com', '12345', 'admin')
ON CONFLICT (email) DO NOTHING;

-- Insert sample site content
INSERT INTO site_content (section, title, content) VALUES
('home', 'Welcome to PTL Best Tinapa', 'Welcome to PTL Best Tinapa in Bulacan. We provide the finest smoked fish products, crafted with generational expertise and modern quality standards. Our premium tinapa is sourced from the freshest fish and smoked using traditional methods passed down through generations.'),
('about', 'About PTL Best Tinapa', 'PTL Best Tinapa has been serving the Bulacan community with premium smoked fish products for over 20 years. Our commitment to quality, tradition, and customer satisfaction has made us the trusted choice for authentic Filipino smoked fish.')
ON CONFLICT (section) DO UPDATE SET
    title = EXCLUDED.title,
    content = EXCLUDED.content,
    updated_at = CURRENT_TIMESTAMP;

-- Insert sample services/products
INSERT INTO services (title, description, price) VALUES
('Premium Tinapa Regular', 'Our signature smoked fish product, perfectly seasoned and smoked to perfection. 500g pack.', 250.00),
('Premium Tinapa Large', 'Larger portion of our premium smoked fish. Perfect for families. 1kg pack.', 450.00),
('Tinapa Boneless', 'Deboned smoked fish for easy eating. Great for sandwiches and salads. 400g pack.', 300.00),
('Smoked Bangus', 'Traditional smoked milkfish, a Filipino favorite. Whole fish, 800g.', 350.00)
ON CONFLICT DO NOTHING;

-- Insert default admin user (password is plain text for now - change this)
INSERT INTO admin (name, email, password) VALUES 
('Admin', 'admin@tinapa.com', '12345');

-- Insert sample content sections
INSERT INTO site_content (section, title, content) VALUES
('home', 'Welcome to PTL Best Tinapa', 'Welcome to PTL Best Tinapa in Bulacan. We provide the finest smoked fish products.'),
('about', 'About Us', 'About our company information goes here.'),
('services', 'Our Services', 'View our products and services.'),
('contact', 'Contact Us', 'Get in touch with us for inquiries.');

-- Insert sample services/products
INSERT INTO services (title, description, image, price) VALUES
('Premium Tinapa', 'Our best quality smoked fish', 'tinapa1.jpg', 250.00),
('Bangus', 'Standard smoked fish', 'bangus.jpg', 150.00),
('Spicy Tinapa', 'Spiced smoked fish', 'tinapa3.jpg', 200.00);
