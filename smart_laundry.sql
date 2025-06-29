USE smart_laundry;

-- Users Registration Table
 -- Clear existing data if needed
CREATE TABLE sign_up (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_profile (
    user_id INT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    date_of_birth DATE,
    address TEXT NOT NULL,       
    permanent_address TEXT,
    education VARCHAR(100),
    occupation VARCHAR(100),
    profile_picture VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id) ON DELETE CASCADE
);


-- Laundry Owner Table (if needed for admin management)
CREATE TABLE owner (
    owner_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT,
    profile_picture VARCHAR(255)
);

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    product_type VARCHAR(100),
    product_quantity INT,
    number_of_products INT,
    pickup_type ENUM('Home Pickup', 'Store Drop') DEFAULT 'Home Pickup',
    delivery_type ENUM('Home Delivery', 'Store Pickup') NOT NULL,
    address TEXT NOT NULL,
    price DECIMAL(10,2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    max_delivery_date DATE,
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id) ON DELETE CASCADE
);

-- Billing Table
CREATE TABLE bill (
    order_id INT PRIMARY KEY,
    user_id INT NOT NULL,
    product_type VARCHAR(100) NOT NULL,
    product_quantity INT NOT NULL,
    billing_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Pending',
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id)
);

-- Service Progress Table
CREATE TABLE service_progress (
    order_id INT PRIMARY KEY,
    service_status ENUM('Received', 'Washing', 'Ironing', 'Completed', 'Delivered') DEFAULT 'Received',
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);


