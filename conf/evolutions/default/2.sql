# Users schema
 
# --- !Ups

ALTER TABLE package ADD COLUMN `license` SMALLINT(6) NOT NULL DEFAULT 0;

# --- !Downs

ALTER TABLE package DROP COLUMN `license`;