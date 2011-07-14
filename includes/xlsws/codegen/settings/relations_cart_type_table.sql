CREATE TABLE xlsws_cart_type (
  id int(10) NOT NULL auto_increment,
  name varchar(255) NOT NULL,
  PRIMARY KEY(`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

insert into xlsws_cart_type VALUES(1, 'cart');

insert into xlsws_cart_type VALUES(2, 'giftregistry');

insert into xlsws_cart_type VALUES(3, 'quote');

insert into xlsws_cart_type VALUES(4, 'order');

insert into xlsws_cart_type VALUES(5, 'invoice');

insert into xlsws_cart_type VALUES(6, 'saved');

insert into xlsws_cart_type VALUES(7, 'awaitpayment');

insert into xlsws_cart_type VALUES(8, 'sro');

