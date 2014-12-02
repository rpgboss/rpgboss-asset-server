# Users schema
 
# --- !Ups
 
CREATE TABLE user (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(80) NOT NULL,
  display_name VARCHAR(50) NOT NULL,
  password CHAR(41) NOT NULL,
  session VARCHAR(100) NOT NULL,
  admin INT(1) NOT NULL DEFAULT 0,
  activated INT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO user VALUES (NULL, "info@rpgboss.com","The Admin","a94a8fe5ccb19ba61c4c0873d391e987982fbbd3","f6b970054ea32e1d87bb26f3076980cdc51255a8", 1,1, NULL);
INSERT INTO user VALUES (NULL, "test@rpgboss.com","The User","a94a8fe5ccb19ba61c4c0873d391e987982fbbd3","f6b970054ea32e1d87bb26f3076980cdc51255a8", 1,0, NULL);

CREATE TABLE category (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO category VALUES (NULL, "Animation","animation");
INSERT INTO category VALUES (NULL, "Battle Background","battle-background");
INSERT INTO category VALUES (NULL, "Battler","battler");
INSERT INTO category VALUES (NULL, "Music","music");
INSERT INTO category VALUES (NULL, "Picture","picture");
INSERT INTO category VALUES (NULL, "Script","script");
INSERT INTO category VALUES (NULL, "Sound","sound");
INSERT INTO category VALUES (NULL, "Spriteset","spriteset");
INSERT INTO category VALUES (NULL, "Tileset","tileset");
INSERT INTO category VALUES (NULL, "Windowskin","windowskin");
INSERT INTO category VALUES (NULL, "Project","project");

CREATE TABLE settings (
  id INT NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(80) NOT NULL DEFAULT "",
  `value` VARCHAR(255) NOT NULL DEFAULT "",
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO settings VALUES (NULL, "LoggedInToDownload","1");
INSERT INTO settings VALUES (NULL, "PackagesNeedToBeVerifiedByAdmin","1");

CREATE TABLE package (
  id INT NOT NULL AUTO_INCREMENT,
  category_id INT NOT NULL,
  user_id INT NOT NULL,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL,
  url VARCHAR(255) NOT NULL,
  pictures text NOT NULL,
  description text NOT NULL,
  verified INT(1) NOT NULL DEFAULT 0,
  rejection_text VARCHAR(255) NOT NULL DEFAULT "",
  version VARCHAR(30) NOT NULL DEFAULT "",
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO package VALUES (NULL, 1,1,"My Animation","my-animation","url","","This is my Animation", 1,"","1.0",NULL);
INSERT INTO package VALUES (NULL, 2,1,"My Unverified Package","unverified-package","url","","This is my unverified package.", 0,"","0.5 Beta",NULL);

CREATE TABLE comment (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  package_id INT NOT NULL,
  rating INT(1) NOT NULL,
  content text NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO comment VALUES (NULL, 1,1,5,"This is a good package. No Problems.",NULL);
 
# --- !Downs
 
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS package;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS settings;