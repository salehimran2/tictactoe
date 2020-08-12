USE test_ggorlen;

DROP TABLE IF EXISTS ttt_stats;

CREATE TABLE IF NOT EXISTS `ttt_stats` (
  `id` int(10) unsigned NOT NULL,
  `wins` int(10) unsigned DEFAULT 0,
  `losses` int(10) unsigned DEFAULT 0,
  `draws` int(10) unsigned DEFAULT 0,
  FOREIGN KEY (`id`) REFERENCES ttt_users(`id`) ON DELETE CASCADE
);
