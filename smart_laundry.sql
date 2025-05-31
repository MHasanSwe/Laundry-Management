CREATE TABLE Sign_Up (
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE User_Profile (
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) PRIMARY KEY,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    profile_picture VARCHAR(255),
    FOREIGN KEY (email) REFERENCES Sign_Up(email) ON DELETE CASCADE
);


CREATE TABLE Owner (
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) PRIMARY KEY,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT,
    profile_picture VARCHAR(255)
);


CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100) NOT NULL,
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
    FOREIGN KEY (email) REFERENCES User_Profile(email) ON DELETE CASCADE
);

CREATE TABLE Bill (
    order_id INT PRIMARY KEY,
    customer_name VARCHAR(100),
    email VARCHAR(100),
    phone_number VARCHAR(15),
    product_type VARCHAR(100) NOT NULL,
    product_quantity INT NOT NULL,
    billing_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Pending',
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (email) REFERENCES User_Profile(email)
);


CREATE TABLE Service_Progress (
    order_id INT PRIMARY KEY,
    service_status ENUM('Received', 'Washing', 'Ironing', 'Completed', 'Delivered') DEFAULT 'Received',
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);
