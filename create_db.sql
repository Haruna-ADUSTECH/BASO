CREATE DATABASE baso_course_repo;
USE baso_course_repo;

CREATE TABLE lecturers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT,
    title VARCHAR(255),
    description TEXT,
    file_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lecturer_id) REFERENCES lecturers(id)
);
