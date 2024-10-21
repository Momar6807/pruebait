-- Active: 1715344762837@@127.0.0.1@3306@pruebait
-- crear base de datos

CREATE DATABASE IF NOT EXISTS pruebait;

use pruebait;
-- tabla de usuarios
CREATE TABLE users (
    id int not null AUTO_INCREMENT primary key,
    username varchar(255) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- insertar usuario de prueba
INSERT INTO
    users (
        username,
        password,
        email,
        first_name,
        last_name
    )
VALUES (
        'admin',
        'root',
        'admin@mail.com',
        'Admin',
        'Superuser'
    );