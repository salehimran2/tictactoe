use test_ggorlen;

DROP TABLE IF EXISTS ttt_confirmations;

CREATE TABLE IF NOT EXISTS `ttt_confirmations` (
  `confirmation_code` int(10) unsigned NOT NULL UNIQUE,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`confirmation_code`),
  FOREIGN KEY (`user_id`) REFERENCES ttt_users(`id`) ON DELETE CASCADE
);
