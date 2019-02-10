+-------+-----------------------------------------------------------+
| Table | Create Table                                                                                                                                                                                                                                                                           |
+-------+-----------------------------------------------------------+
| user  | CREATE TABLE `user` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 |
+-------+-----------------------------------------------------------+

+-------+-----------------------------------------------------------+
| event | CREATE TABLE `event` (
  `event_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `event_user_id` mediumint(8) unsigned NOT NULL,
  `event_title` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) NOT NULL,
  `event_location` varchar(50) NOT NULL,
  `event_tag` varchar(5) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `event_user_id` (`event_user_id`),
  CONSTRAINT `event_ibfk_1` FOREIGN KEY (`event_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 |
+-------+-----------------------------------------------------------+

+-------+-----------------------------------------------------------+
| date  | CREATE TABLE `date` (
  `date_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `date_user_id` mediumint(8) unsigned NOT NULL,
  `date_number` varchar(20) NOT NULL,
  PRIMARY KEY (`date_id`),
  KEY `date_user_id` (`date_user_id`),
  CONSTRAINT `date_ibfk_1` FOREIGN KEY (`date_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 |
+-------+-----------------------------------------------------------+
