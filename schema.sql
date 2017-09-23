CREATE DATABASE yeticave_181305;
USE yeticave_181305;

CREATE TABLE users (
	id 				    INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
	email 			  VARCHAR (254) NOT NULL,
	passwordHash 	VARCHAR(60) 	NOT NULL,
	name 				  VARCHAR(40) 	NOT NULL,
  avatarUrl 		VARCHAR(150),
	contacts 		  VARCHAR(200) 	NOT NULL,
	createdTime 	DATETIME
);

CREATE TABLE categories (
  id 		INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
  name 	VARCHAR(40) 	NOT NULL
);

CREATE TABLE lots (
  id 					    INT UNSIGNED 	AUTO_INCREMENT PRIMARY KEY,
  name 				    VARCHAR(150) 	NOT NULL,
  cost 				    DOUBLE 			  NOT NULL,
  imgUrl 			    VARCHAR(150) 	NOT NULL,
  description 		TEXT(600) 		NOT NULL,
  endTime 	      DATETIME 		  NOT NULL,
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
  cost 			    DOUBLE        NOT NULL,
  userId 		    INT UNSIGNED,
  lotId 			  INT UNSIGNED
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX name ON categories(name);