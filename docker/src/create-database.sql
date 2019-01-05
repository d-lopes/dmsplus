/* create database */
CREATE DATABASE IF NOT EXISTS seeddms;

/* grant all access rights to db user */ 
CREATE USER IF NOT EXISTS 'seeddms'@'localhost' IDENTIFIED WITH mysql_native_password AS 's3cret';

SET PASSWORD FOR 'seeddms'@'localhost' = PASSWORD('s3cret');
GRANT USAGE ON *.* TO 'seeddms'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

GRANT ALL PRIVILEGES ON seeddms.* TO 'seeddms'@'localhost';