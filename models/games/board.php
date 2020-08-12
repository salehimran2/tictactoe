<?php

/**
 * Board interface
 */
interface Board {
    public function getBoard();
    public function deepCopy();
    public function isWon();
    public function isDrawn();
    public function move($square);
    public function scoreWin();
    public function scoreDraw();
} // end Board

?>
