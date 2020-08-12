USE test_ggorlen;

DROP TABLE IF EXISTS ttt_moves;

CREATE TABLE IF NOT EXISTS `ttt_moves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL, 
  `ply` int(10) unsigned NOT NULL,
  `end_location` int(10) unsigned NOT NULL,
  `start_location` int(10) unsigned,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`game_id`) REFERENCES ttt_games(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`player_id`) REFERENCES ttt_users(`id`) ON DELETE CASCADE
);
