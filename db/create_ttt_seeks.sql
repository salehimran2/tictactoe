USE test_ggorlen;

DROP TABLE IF EXISTS ttt_seeks;

CREATE TABLE IF NOT EXISTS `ttt_seeks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `timestamp` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES ttt_users(`id`) ON DELETE CASCADE
);
