CREATE DATABASE newac_db;

USE newac_db;

CREATE TABLE scan_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hostname VARCHAR(255),
    portid VARCHAR(10),
    protocol VARCHAR(10),
    state VARCHAR(10),
    service VARCHAR(255),
    product VARCHAR(255)
);