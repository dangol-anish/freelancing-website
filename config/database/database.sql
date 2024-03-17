CREATE DATABASE freelancing_website;

-- user table
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT ,
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
    skill_id INT PRIMARY KEY AUTO_INCREMENT,
    skill_name VARCHAR(255) UNIQUE NOT NULL,
    skill_category VARCHAR(255) NOT NULL,
    skill_approval TINYINT NOT NULL  
);

-- freelancer skill table

CREATE TABLE freelancer_skill (
    fs_id  INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT ,
    skill_id INT,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE

);

-- job skill

CREATE TABLE job_skill(
    js_id  INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT ,
    skill_id INT,
    CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE

);

-- freelancer table

CREATE TABLE freelancer (
    freelancer_id INT  NOT NULL PRIMARY KEY AUTO_INCREMENT,
    freelancer_identity_photo VARCHAR(255) NOT NULL,
    freelancer_verification_photo VARCHAR(255) NOT NULL,
    freelancer_bio VARCHAR(255) NOT NULL,
    freelancer_cv VARCHAR(255),
    user_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
    
);

-- client table

CREATE TABLE client (
    client_id INT  NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    client_pan_photo VARCHAR(255) NOT NULL,
    client_verification_photo VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
     CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- job category


CREATE TABLE job (
    job_id INT  NOT NULL PRIMARY KEY AUTO_INCREMENT,
    job_title VARCHAR(255) NOT NULL,
    job_description VARCHAR(255) NOT NULL,
    job_budget VARCHAR(255) NOT NULL,
    job_duration VARCHAR(255) NOT NULL,
    job_status VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE  
);

CREATE TABLE job_skill(
    js_id  INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT ,
    skill_id INT,
    CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE
);



-- job application

create table job_application(
    ja_id  INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT ,
    client_user_id INT,
    freelancer_user_id INT,
    CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE
);



create table messages (
messages_id  INT PRIMARY KEY AUTO_INCREMENT,
 client_user_id INT,
    freelancer_user_id INT,
    message varchar(1000),
    job_id int,
    CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE

);


create table job_close (
jc_id int primary AUTO_INCREMENT,
requester_id int,
responder_id int,
job_id int,
 CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE
);



create table freelancer_rating(
 rating_id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT,
    job_id INT,
    freelancer_user_id INT,
    client_user_id INT,
 CONSTRAINT FOREIGN KEY (job_id) REFERENCES job(job_id) ON DELETE CASCADE
);