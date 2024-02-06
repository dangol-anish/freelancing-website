CREATE DATABASE freelancing_website;

-- user table
CREATE TABLE user (
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

create TABLE skill (
    skill_id SERIAL PRIMARY KEY,
    skill_name VARCHAR(255) UNIQUE NOT NULL,
    skill_category VARCHAR(255) NOT NULL,
    skill_approval TINYINT NOT NULL  
);

-- freelancer skill table

CREATE TABLE freelancer_Skill (
    freelancer_id INT REFERENCES user(user_id) ON DELETE CASCADE,
    skill_id INT REFERENCES skill(skill_id) ON DELETE CASCADE,
    PRIMARY KEY (freelancer_id, skill_id)
);

-- freelancer table

CREATE TABLE freelancer (
     user_id SERIAL NOT NULL PRIMARY KEY,
    freelancer_identity_photo VARCHAR(255) NOT NULL,
    freelancer_verification_photo VARCHAR(255) NOT NULL,
    freelancer_bio VARCHAR(255) NOT NULL,
    freelancer_cv VARCHAR(255),
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
    
);


-- 
-- CREATE TABLE freelancer (
--     user_id INT,
--     freelancer_identity_photo VARCHAR(255) NOT NULL,
--     freelancer_verification_photo VARCHAR(255) NOT NULL,
--     freelancer_bio VARCHAR(255) NOT NULL,
--     freelancer_cv VARCHAR(255),
--     CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
-- );


-- client table

CREATE TABLE Client (
    client_id SERIAL PRIMARY KEY ,
    client_pan_photo VARCHAR(255) NOT NULL,
    client_verification_photo VARCHAR(255) NOT NULL,
    user_id INT REFERENCES User(user_id) ON DELETE CASCADE
);