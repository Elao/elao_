# Databases
CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Users
CREATE USER 'app'@'%';
GRANT ALL ON `app`.* TO 'app'@'%';
