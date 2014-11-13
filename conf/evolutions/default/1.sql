# Users schema
 
# --- !Ups
 
CREATE TABLE User (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(80) NOT NULL,
  display_name VARCHAR(50) NOT NULL,
  password CHAR(41) NOT NULL,
  session VARCHAR(100) NOT NULL,
  admin INT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO User VALUES (NULL, "info@rpgboss.com","The Admin","a94a8fe5ccb19ba61c4c0873d391e987982fbbd3","f6b970054ea32e1d87bb26f3076980cdc51255a8", 1, NULL);
INSERT INTO User VALUES (NULL, "test@rpgboss.com","The User","a94a8fe5ccb19ba61c4c0873d391e987982fbbd3","f6b970054ea32e1d87bb26f3076980cdc51255a8", 0, NULL);

CREATE TABLE Category (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO Category VALUES (NULL, "Animation","animation");
INSERT INTO Category VALUES (NULL, "Battle Background","battle-background");
INSERT INTO Category VALUES (NULL, "Battler","battler");
INSERT INTO Category VALUES (NULL, "Music","music");
INSERT INTO Category VALUES (NULL, "Picture","picture");
INSERT INTO Category VALUES (NULL, "Script","script");
INSERT INTO Category VALUES (NULL, "Sound","sound");
INSERT INTO Category VALUES (NULL, "Spriteset","spriteset");
INSERT INTO Category VALUES (NULL, "Tileset","tileset");
INSERT INTO Category VALUES (NULL, "Windowskin","windowskin");
INSERT INTO Category VALUES (NULL, "Project","project");

CREATE TABLE Package (
  id INT NOT NULL AUTO_INCREMENT,
  category_id INT NOT NULL,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  url VARCHAR(255) NOT NULL,
  pictures text NOT NULL,
  description text NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE Comment (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  package_id INT NOT NULL,
  rating INT(1) NOT NULL,
  content text NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;
 
# --- !Downs
 
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Package;
DROP TABLE IF EXISTS Comment;