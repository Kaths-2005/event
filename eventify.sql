-- Create the eventify database
CREATE DATABASE IF NOT EXISTS eventify;
USE eventify;

-- Create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    pid_no VARCHAR(6) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create events table
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT NOT NULL,
    event_image VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL
);

-- Create reactions table
CREATE TABLE reactions (
    reaction_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    reaction_type VARCHAR(10),  -- ('heart', 'thumbs_up', 'thumbs_down', 'firecracker')
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create comments table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    comment TEXT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);