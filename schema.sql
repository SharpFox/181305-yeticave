CREATE DATABASE yeticave_181305;
USE yeticave_181305;

CREATE TABLE users (
	id 				    INT UNSIGNED 	AUTO_INCREMENT,
	email 			  VARCHAR (254) NOT NULL,
	passwordHash 	VARCHAR(60) 	NOT NULL,
	name 				  VARCHAR(40) 	NOT NULL,
  url 		VARCHAR(150),
	contacts 		  VARCHAR(200) 	NOT NULL,
	createdTime 	DATETIME,
  PRIMARY KEY(id)
) ENGINE = InnoDB CHARACTER SET = UTF8;

CREATE TABLE categories (
  id 		INT UNSIGNED 	AUTO_INCREMENT,
  name 	VARCHAR(40) 	NOT NULL,
  PRIMARY KEY(id)
) ENGINE = InnoDB CHARACTER SET = UTF8;

CREATE TABLE lots (
  id 					    INT UNSIGNED 	AUTO_INCREMENT,
  name 				    VARCHAR(150) 	NOT NULL,
  cost 				    DOUBLE 			  NOT NULL,
  url 			    VARCHAR(150) 	NOT NULL,
  description 		TEXT(600) 		NOT NULL,
  endTime 	      DATETIME 		  NOT NULL,
  step 				    INT UNSIGNED 	NOT NULL,
  quantityBets    INT UNSIGNED  NOT NULL,
  categoryId 		  INT UNSIGNED,
  authorId 		    INT UNSIGNED,
  winnerId 		    INT UNSIGNED,
  createdTime 		DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY (categoryId) REFERENCES categories(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (authorId) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (winnerId) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE = InnoDB CHARACTER SET = UTF8;

CREATE TABLE bets (
  id 				    INT UNSIGNED  AUTO_INCREMENT,
  createdTime   DATETIME,
  endTime       DATETIME 		  NOT NULL,
  cost 			    DOUBLE        NOT NULL,
  userId 		    INT UNSIGNED,
  lotId 			  INT UNSIGNED,
  PRIMARY KEY(id),
  FOREIGN KEY (userId) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (lotId) REFERENCES lots(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE = InnoDB CHARACTER SET = UTF8;

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX name ON categories(name);