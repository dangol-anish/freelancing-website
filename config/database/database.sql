CREATE DATABASE freelancing_website;

-- user table
CREATE TABLE User (
    user_id SERIAL PRIMARY KEY,
    user_first_name VARCHAR(255),
    user_last_name VARCHAR(255),
    user_password VARCHAR(255),
    user_email VARCHAR(255) UNIQUE,
    user_phone_number VARCHAR(255),
    user_type VARCHAR(50),
    user_photo VARCHAR(255)
);
