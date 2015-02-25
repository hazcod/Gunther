# IN A DEFAULT SESSION; (su - postgres   ->   psql)
SET search_path to public;
CREATE DATABASE gunther;

# IN GUNTHER;  (psql -d gunther) 
CREATE TABLE users (
id SERIAL NOT NULL,
login VARCHAR(10) NOT NULL,
password VARCHAR(255) NOT NULL,
created TIMESTAMP NOT NULL DEFAULT(NOW()),
email TEXT,
lastseen TIMESTAMP,
role INT NOT NULL,
PRIMARY KEY (id)
);
CREATE TABLE langs (
id SERIAL NOT NULL,
name VARCHAR(15) NOT NULL,
flag VARCHAR(2) NOT NULL,
PRIMARY KEY (id)
);
CREATE TABLE roles (
id SERIAL NOT NULL,
name VARCHAR(20) NOT NULL,
PRIMARY KEY (id)
);
ALTER TABLE users ADD CONSTRAINT users_fk1 FOREIGN KEY (role) REFERENCES roles(id);
INSERT INTO roles (name) VALUES ('admin');
INSERT INTO roles (name) VALUES ('regular');
INSERT INTO users (login, password, role) VALUES ('admin', '$2y$10$yG.0inUhckrKM1kGXcjUEe4U60iEcdpfn3bVyW.1EPEd2DaLKGauq', 1);
INSERT INTO langs (name, flag) VALUES ('English', 'en');
CREATE USER gunther WITH PASSWORD '<PASSWORD>';
grant all privileges on all tables in schema public to gunther;
