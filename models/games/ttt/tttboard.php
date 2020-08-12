<?php

/**
 * Class representing tic tac toe game logic 
 */
class TicTacToeBoard implements Board {
    private $xMoves;
    private $oMoves;
    private $ply;

    // List of win positions in tic tac toe
    public static $winPositions = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], 
        [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]
    ];

    /**
     * Constructor for a tic tac toe game board
     */
    public function __construct($xMoves, $oMoves, $ply) {
        $this->xMoves = $xMoves;
        $this->oMoves = $oMoves;
        $this->ply = $ply;
    } // end __construct

    /**
     * Returns the board as an array
     *
     * @return the board array
     */
    public function getBoard() {
        $board = [];

        for ($i = 0; $i < 9; $i++) {
            if (array_key_exists($i, $this->xMoves)) {
                $board[]= "x";
            }
            else if (array_key_exists($i, $this->oMoves)) {
                $board[]= "o";
            }
            else {
                $board[]= " ";
            }
        }

        return $board;
    } // end getBoard

    /**
     * Moves the current player to the parameter square
     *
     * @param square the destination square in the range 0-8
     * @return true if move was executed, false otherwise
     */
    public function move($square) {
        if ($square >= 0 && $square <= 8 && !$this->isWon() && !$this->isDrawn()) { 
            if ($this->ply & 1 && !array_key_exists($square, $this->oMoves)) {
                $this->oMoves[$square] = true;
                $this->ply++;
                return true;
            }
            else if (!($this->ply & 1) && !array_key_exists($square, $this->xMoves)) {
                $this->xMoves[$square] = true;
                $this->ply++;
                return true;
            }
        }
      
        return false;
    } // end move

    /**
     * Returns whether the current position has a winner
     *
     * @return true if a player has won, false otherwise
     */
    public function isWon() {
         if ($this->ply >= 5) {
             for ($i = 0; $i < count(TicTacToeBoard::$winPositions); $i++) {
                 $xWon = true;
                 $oWon = true;
                
                 for ($j = 0; $j < count(TicTacToeBoard::$winPositions[$i]) && ($xWon || $oWon); $j++) {
                     if (!array_key_exists(TicTacToeBoard::$winPositions[$i][$j], $this->xMoves)) {
                         $xWon = false;
                     }
                     if (!array_key_exists(TicTacToeBoard::$winPositions[$i][$j], $this->oMoves)) {
                         $oWon = false;
                     }
                 }
                
                 if ($xWon || $oWon) { return true; }
             }
         }
         
         return false;
    } // end isWon
    
    /**
     * Determines whether the game is drawn
     * Note: must be called after isWon()
     *
     * @return true if drawn
     */
    public function isDrawn() {
        return $this->ply >= 9;
    } // end isDrawn
    
    /**
     * Returns an array of valid moves for this board
     *
     * @return valid moves array
     */
    public function getMoves() {
        $moves = [];
        
        for ($i = 0; $i < 9; $i++) {
            if (!array_key_exists($i, $this->xMoves) &&
                !array_key_exists($i, $this->oMoves)) {
                $moves[]= $i;
            }
        }
        
        return $moves;
    } // end getMoves

    /**
     * Returns the ply
     *
     * @return integer ply
     */
    public function getPly() {
        return $this->ply;
    } // end getPly 
    
    /**
     * Produces a deep copy of this object
     *
     * @return TicTacToeBoard clone
     */
    public function deepCopy() {
        $ttt = new TicTacToeBoard();
        $ttt->xMoves = [];
        $ttt->oMoves = [];
        
        foreach ($this->xMoves as $xMove) {
            $ttt->xMoves[]= $xMove;
        }
        
        foreach ($this->oMoves as $oMove) {
            $ttt->oMoves[]= $oMove;
        }
        
        $ttt->ply = $this->ply;
        return $ttt;
    } // end deepCopy
    
    /**
     * Scores a won position based on ply
     *
     * @return the score rating
     */
    public function scoreWin() {
        return 10 - $this->ply;
    } // end scoreWin
    
    /**
     * Scores a drawn position
     *
     * @return the drawn position rating
     */
    public function scoreDraw() {
        return 0;
    } // end scoreDraw
} // end TicTacToeBoard

?>
