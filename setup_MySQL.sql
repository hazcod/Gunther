SET AUTOCOMMIT=0; SET FOREIGN_KEY_CHECKS=0;
CREATE DATABASE gunther;
USE gunther;
CREATE TABLE users (
id int unsigned auto_increment NOT NULL,
login VARCHAR(10) NOT NULL,
password VARCHAR(255) NOT NULL,
created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
email TEXT,
lastseen TIMESTAMP,
role int unsigned NOT NULL,
PRIMARY KEY (id),
INDEX (role),
FOREIGN KEY (role) REFERENCES roles(id)
);
CREATE TABLE langs (
id int unsigned auto_increment NOT NULL,
name VARCHAR(15) NOT NULL,
flag VARCHAR(2) NOT NULL,
PRIMARY KEY (id)
);
CREATE TABLE roles (
id int unsigned auto_increment NOT NULL,
name VARCHAR(20) NOT NULL,
PRIMARY KEY (id)
);
INSERT INTO roles (name) VALUES ('admin');
INSERT INTO roles (name) VALUES ('regular');
INSERT INTO users (login, password, role) VALUES ('admin', '$2y$10$yG.0inUhckrKM1kGXcjUEe4U60iEcdpfn3bVyW.1EPEd2DaLKGauq', 1);
INSERT INTO langs (name, flag) VALUES ('English', 'en');
CREATE USER gunther IDENTIFIED BY '<PASSWORD>';
GRANT ALL PRIVILEGES ON gunther to gunther;
SET FOREIGN_KEY_CHECKS=1; COMMIT; SET AUTOCOMMIT=1;
FLUSH PRIVILEGES;
