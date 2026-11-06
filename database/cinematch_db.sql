CREATE DATABASE IF NOT EXISTS cinematch
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cinematch;

CREATE TABLE IF NOT EXISTS movies (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    synopsis TEXT,
    release_year YEAR,
    rating DECIMAL(3, 1),
    PRIMARY KEY (id)
);
