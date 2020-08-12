<?php

/**
 * Game interface
 */
interface Game {
    public function getBoard();
    public function getPlayer1();
    public function getPlayer2();
    public function getCurrentPlayer();
    public function getResult();
    public function getPly();
    public function getEndTime();
    public function getStartTime();
    public function getMoveTimeLimit();
    public function getGameTimeLimit();
    public function move($playerId, $square);
} // end Game

?>
