CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('student', 'faculty') NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fathers_name VARCHAR(100) DEFAULT NULL,
    roll_no VARCHAR(50) DEFAULT NULL,
    year VARCHAR(20) DEFAULT NULL,
    branch VARCHAR(100) DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
