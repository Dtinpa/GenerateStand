CREATE DATABASE quirkcreation;
USE quirkcreation;

CREATE USER 'dtinva'@'localhost' IDENTIFIED BY 'p@triot1';
GRANT ALL PRIVILEGES ON quirkcreation.* TO 'dtinva'@'localhost' with grant option;

CREATE USER 'dtinva'@'ubuntu2204.localdomain' IDENTIFIED BY 'p@triot1';
GRANT ALL PRIVILEGES ON quirkcreation.* TO 'dtinva'@'ubuntu2204.localdomain' with grant option;