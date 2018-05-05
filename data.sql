/*CREATE TABLE form (
    sno INT(3) NOT NULL AUTO_INCREMENT,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(30) NOT NULL,
    username VARCHAR(10) NOT NULL UNIQUE,
    password VARCHAR(10) NOT NULL,
    date TIMESTAMP,
    PRIMARY KEY (sno)
);*/

ALTER TABLE form DROP date;