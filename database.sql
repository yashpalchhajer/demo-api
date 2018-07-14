/**
database schema 
*/

create database `inst_demo`;

CREATE TABLE `inst_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `disp_name` varchar(50) DEFAULT NULL,
  `email_id` varchar(50) NOT NULL,
  `user_type` enum('U','A','M') DEFAULT 'U' COMMENT 'U user, A admin, M master admin',
  `password` varchar(50) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `inst_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(10) DEFAULT NULL,
  `order_amount` double DEFAULT NULL,
  `order_status` enum('SUCCESS','CANCELLED') DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


CREATE TABLE `inst_orders_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(10) NOT NULL,
  `item_name` varchar(25) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

insert into inst_users (user_name,disp_name,email_id,user_type,password,mobile,created_at) values
('testAdmin','Test','test@admin.com','A',PASSWORD('Test@1234'),'9509801562',now()),
('userAdmin','User','user@admin.com','U',PASSWORD('User@1234'),'7052632569',now());

insert into inst_orders (user_id,order_id,order_amount,order_status,created_at) VALUES 
(1,101301,205.86,'SUCCESS',now()),
(1,101302,125,'SUCCESS',now()),
(2,101303,420,'SUCCESS',now());

INSERT into inst_orders_details (order_id,item_name,quantity,unit_price) VALUES
(101301,'Veg Thali',1,190),
(101302,'Chocklate Shake',1,70),
(101303,'Veg Thali',1,190),
(101303,'Cocklate Shake',1,70),
(101303,'Fruit Cream',2,75);

