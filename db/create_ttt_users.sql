use test_ggorlen;

DROP TABLE IF EXISTS ttt_users;

CREATE TABLE IF NOT EXISTS `ttt_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `permissions` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);
