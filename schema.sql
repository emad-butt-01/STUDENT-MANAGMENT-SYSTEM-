-- Student Management System — database schema
-- Import this file in phpMyAdmin (or via `mysql -u root -p < schema.sql`) to
-- create the database used by the app.

CREATE DATABASE IF NOT EXISTS student_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE student_db;

-- Accounts that can log in to the dashboard.
CREATE TABLE IF NOT EXISTS register (
    st_id     INT AUTO_INCREMENT PRIMARY KEY,
    fname     VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,   -- bcrypt hash, NOT plain text
    cpassword VARCHAR(255) NOT NULL    -- legacy column kept for backwards compatibility
);

-- Student records managed from the dashboard.
CREATE TABLE IF NOT EXISTS student_detail (
    st_id       INT AUTO_INCREMENT PRIMARY KEY,
    fname       VARCHAR(100) NOT NULL,
    lname       VARCHAR(100) NOT NULL,
    father_name VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    mobile      VARCHAR(20)  NOT NULL,
    gender      VARCHAR(10)  NOT NULL,
    district    VARCHAR(100) NOT NULL,
    city        VARCHAR(100) NOT NULL,
    state       VARCHAR(100) NOT NULL,
    nation      VARCHAR(100) NOT NULL,
    photo       VARCHAR(255) NOT NULL
);
