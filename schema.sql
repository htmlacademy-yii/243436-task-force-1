CREATE DATABASE taskforce
  DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


USE taskforce;

CREATE TABLE categories (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	icon VARCHAR(20) NOT NULL
);

CREATE TABLE cities (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	city VARCHAR(100) NOT NULL,
	lat DOUBLE NOT NULL,
	lon DOUBLE NOT NULL
);

CREATE TABLE opinions (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_add DATETIME NOT NULL,
	rate INT UNSIGNED NOT NULL,
	description TEXT NOT NULL
);

CREATE TABLE profiles (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	city_id INT UNSIGNED NOT NULL,
	birthday DATETIME NOT NULL,
	about TEXT NOT NULL,
	phone VARCHAR(100) NOT NULL,
	skype VARCHAR(100) NOT NULL
);

CREATE TABLE replies (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_add DATETIME NOT NULL,
	rate INT UNSIGNED NOT NULL,
	description TEXT NOT NULL
);

CREATE TABLE users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(72) NOT NULL,
	name VARCHAR(100) NOT NULL,
	password CHAR(64) NOT NULL,
  date_add DATETIME NOT NULL
);

CREATE TABLE tasks (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_add DATETIME NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  path VARCHAR(100),
	description TEXT NOT NULL,
  expire DATETIME,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(700),
  budget INT UNSIGNED,
  lat DOUBLE,
	lon DOUBLE,
  city_id INT UNSIGNED,
  user_id_create INT UNSIGNED NOT NULL,
  user_id_executor INT UNSIGNED,
  status VARCHAR(100) NOT NULL
);

CREATE TABLE users_and_categories (
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL
);

CREATE TABLE messages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  message TEXT NOT NULL,
  user_id_create INT UNSIGNED,
  user_id_executor INT UNSIGNED
);

ALTER TABLE tasks ADD FOREIGN KEY (category_id) REFERENCES categories(id);
ALTER TABLE tasks ADD FOREIGN KEY (city_id) REFERENCES cities(id);
ALTER TABLE tasks ADD FOREIGN KEY (user_id_create) REFERENCES users(id);
ALTER TABLE tasks ADD FOREIGN KEY (user_id_executor) REFERENCES users(id);
ALTER TABLE profiles ADD FOREIGN KEY (city_id) REFERENCES cities(id);
ALTER TABLE users_and_categories ADD FOREIGN KEY (user_id) REFERENCES users(id);
ALTER TABLE users_and_categories ADD FOREIGN KEY (category_id) REFERENCES categories(id);
ALTER TABLE messages ADD FOREIGN KEY (user_id_create) REFERENCES users(id);
ALTER TABLE messages ADD FOREIGN KEY (user_id_executor) REFERENCES users(id);
