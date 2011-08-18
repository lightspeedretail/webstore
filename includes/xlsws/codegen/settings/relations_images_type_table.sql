CREATE TABLE xlsws_images_type (
  id int(10) NOT NULL auto_increment,
  name varchar(255) NOT NULL,
  width int(5) NOT NULL,
  height int(5) NOT NULL,
  PRIMARY KEY(`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `size` (`width`,`height`)
) ENGINE=MyISAM;

INSERT INTO `xlsws_images_type` VALUES
(1, 'image', 0, 0),
(2, 'smallimage', 90, 90),
(3, 'pdetailimage', 256, 256),
(4, 'miniimage', 30, 30),
(5, 'listingimage', 40, 40);

