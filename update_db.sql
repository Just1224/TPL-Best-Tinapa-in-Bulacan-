-- Create separate users table for regular customers
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add role column to admin table if it doesn't exist
ALTER TABLE admin ADD COLUMN role VARCHAR(50) DEFAULT 'admin' AFTER password;

-- Update existing admin users
UPDATE admin SET role = 'admin' WHERE role IS NULL;
