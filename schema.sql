-- E-Voting System Database Schema

-- Create the voting database
CREATE DATABASE IF NOT EXISTS voting;
USE voting;

-- Students table
CREATE TABLE IF NOT EXISTS student (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    id_number VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Candidates table
CREATE TABLE IF NOT EXISTS candidate (
    candidate_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    position VARCHAR(100) NOT NULL,
    manifesto LONGTEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Elections table
CREATE TABLE IF NOT EXISTS elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    status VARCHAR(50) DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Votes table
CREATE TABLE IF NOT EXISTS vote (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    election_id INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES student(user_id),
    FOREIGN KEY (candidate_id) REFERENCES candidate(candidate_id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    UNIQUE KEY unique_vote (user_id, election_id)
);

-- Officer table (election officers)
CREATE TABLE IF NOT EXISTS officer (
    officer_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO elections (title, description, start_datetime, end_datetime, status) 
VALUES ('DDU Student Union Elections 2025', 'Vote for your student union representatives', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'ongoing');

INSERT INTO admin (username, password, email) 
VALUES ('admin', '$2y$10$WjHNzZAM1p9JBr6xRyBG9.6Z7N8K5L2M3P4Q5R6S7T8U9V0W1X2Y3', 'admin@ddu.edu');

INSERT INTO officer (username, password, email) 
VALUES ('officer', '$2y$10$WjHNzZAM1p9JBr6xRyBG9.6Z7N8K5L2M3P4Q5R6S7T8U9V0W1X2Y3', 'officer@ddu.edu');

-- Sample candidates
INSERT INTO candidate (full_name, position, manifesto) 
VALUES 
('Alice Johnson', 'President', 'I will work to improve student facilities and increase funding for clubs.'),
('Bob Smith', 'Vice President', 'My focus will be on student welfare and better communication with administration.'),
('Carol White', 'Treasurer', 'I aim to manage the union budget transparently and fairly for all students.');
