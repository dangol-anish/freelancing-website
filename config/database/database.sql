CREATE DATABASE freelancing_website;

-- user table
CREATE TABLE User (
    user_id SERIAL PRIMARY KEY ,
    user_first_name VARCHAR(255) NOT NULL,
    user_last_name VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL ,
    user_email VARCHAR(255) UNIQUE NOT NULL,
    user_phone_number VARCHAR(255) NOT NULL,
    user_type VARCHAR(50) NOT NULL,
    user_photo VARCHAR(255) NOT NULL
);


-- skill table

create TABLE Skill (
    skill_id SERIAL PRIMARY KEY,
    skill_name VARCHAR(255) UNIQUE NOT NULL,
    skill_category VARCHAR(255) NOT NULL
    skill_approval TINYINT NOT NULL  
);

-- freelancer skill table

CREATE TABLE Freelancer_Skill (
    user_id INT REFERENCES User(user_id),
    skill_id INT REFERENCES Skill(skill_id),
    PRIMARY KEY (user_id, skill_id)
);

-- freelancer table

CREATE TABLE Freelancer (
    freelancer_id SERIAL PRIMARY KEY ,
    freelancer_verification_photo VARCHAR(255) REQUIRED,
    freelancer_bio VARCHAR(255) REQUIRED,
    freelancer_cv VARCHAR(255),
    user_id INT REFERENCES User(user_id),

);