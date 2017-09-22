CREATE DATABASE yeticave_181305;
USE yeticave_181305;

CREATE TABLE users (
	id 				    INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
	email 			  CHAR(254) 		NOT NULL,
	passwordHash 	CHAR(80) 		NOT NULL,
	name 				  CHAR(40) 		  NOT NULL,
  avatarUrl 		CHAR(150),
	contacts 		  CHAR(200) 		NOT NULL,
	createdTime 	DATETIME
);

CREATE TABLE categories (
  id 		INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
  name 	CHAR(40) 		  NOT NULL
);

CREATE TABLE lots (
  id 					    INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
  name 				    CHAR(150) 		NOT NULL,
  cost 				    FLOAT 			  NOT NULL,
  imgUrl 			    CHAR(150) 		NOT NULL,
  description 		TEXT(600) 		NOT NULL,
  endTime 	DATETIME 		  NOT NULL,
  step 				    INT UNSIGNED 	NOT NULL,
  quantityBets    INT UNSIGNED  NOT NULL,
  categoryId 		  INT UNSIGNED,
  authorId 		    INT UNSIGNED,
  winnerId 		    INT UNSIGNED,
  createdTime 		DATETIME
);

CREATE TABLE bets (
  id 				    INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
  createdTime   DATETIME,
  endTime       DATETIME 		  NOT NULL,
  cost 			    FLOAT         NOT NULL,
  userId 		    INT UNSIGNED,
  lotId 			  INT UNSIGNED
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX name ON categories(name);