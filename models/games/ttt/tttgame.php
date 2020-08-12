<?php

/**
 * Model for a Tic Tac Toe game
 */
class TicTacToeGame implements Game {
    public const TABLE_NAME = 'ttt_games';
    public const GAME_TYPE = 'tic tac toe';

    private $db;
    private $id;
    private $endTime;
    private $moveTimeLimit;
    private $gameTimeLimit;
    private $board;
    private $player1Id;
    private $player2Id;
    private $player1Username;
    private $player2Username;
    private $result;
    private $startTime;

    /**
     * Constructor for a TicTacToeGame object
     *
     * @param $data a hash of arguments representing a game:
     *              id, end_time, move_time_limit
     */
    public function __construct($data) {
        $this->db = new DB(DBHOST, DBUSER, DBPASS, DATABASE);

        // Fetch instance data from args hash
        $this->id = (int)$this->db->real_escape_string($data->id);
        $this->endTime = (int)$this->db->real_escape_string($data->end_time);
        $this->moveTimeLimit = (int)$this->db->real_escape_string($data->move_time_limit);
        $this->gameTimeLimit = (int)$this->db->real_escape_string($data->game_time_limit);
        $this->result = $this->db->real_escape_string($data->result);
        $this->startTime = (int)$this->db->real_escape_string($data->start_time);
        $this->player1Id = (int)$this->db->real_escape_string($data->player1_id);
        $this->player2Id = (int)$this->db->real_escape_string($data->player2_id);

        // Get player data from db
        $query = "SELECT username FROM ttt_users WHERE id = '$this->player1Id';";
        $result = $this->db->query($query);

        if ($result && $result->num_rows === 1) {
            $this->player1Username = $result->fetch_object()->username;
        }

        $query = "SELECT username FROM ttt_users WHERE id = '$this->player2Id';";
        $result = $this->db->query($query);

        if ($result && $result->num_rows === 1) {
            $this->player2Username = $result->fetch_object()->username;
        }
        
        // Populate move data hashes from db
        $xMoves = [];
        $oMoves = [];

        $query = "SELECT end_location FROM ttt_moves 
                  WHERE game_id = '$this->id' 
                  AND player_id = '" . $this->player1Id . "';";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            while ($moveData = $result->fetch_object()) {
                $xMoves[$moveData->end_location] = true;
            }
        }

        $query = "SELECT end_location FROM ttt_moves 
                  WHERE game_id = '$this->id' 
                  AND player_id = '" . $this->player2Id . "';";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            while ($moveData = $result->fetch_object()) {
                $oMoves[$moveData->end_location] = true;
            }
        }

        // Create instance board
        $this->board = new TicTacToeBoard(
            array_map('intval', $xMoves), 
            array_map('intval', $oMoves), 
            count($xMoves) + count($oMoves)
        );
    } // end __construct

    /**
     * Sets and the result (e.g. win/loss/draw)
     *
     * @return true if result was successfully set, false otherwise
     */
    public function setResult() {
    
        // TODO make transactions

        if ($this->board->isWon()) {
            $this->result = $this->board->getPly() & 1 ? "1-0" : "0-1";
            $this->endTime = time();
            $query = '
                UPDATE ' . TicTacToeGame::TABLE_NAME . " 
                SET result = '$this->result',
                end_time = $this->endTime 
                WHERE id = $this->id;
            ";

            if ($this->db->query($query)) {
                $p1Stat = new Stats($this->player1Id);
                $p2Stat = new Stats($this->player2Id);

                if ($this->result === "1-0") {
                    $p1Stat->addWin();
                    $p2Stat->addLoss();
                }
                else {
                    $p1Stat->addLoss();
                    $p2Stat->addWin();
                }

                return true;
            }
        }
        else if ($this->board->isDrawn()) { 
            $this->result = "0-0";
            $this->endTime = time();
            $query = '
                UPDATE ' . TicTacToeGame::TABLE_NAME . "
                SET result = '$this->result',
                end_time = '$this->endTime' 
                WHERE id = '$this->id';
            ";
            if ($this->db->query($query)) {
                $p1Stat = new Stats($this->player1Id);
                $p2Stat = new Stats($this->player2Id);
                $p1Stat->addDraw();
                $p2Stat->addDraw();
                return true;
            }
        }

        return false;
    } // end setResult

    /**
     * Returns the game board
     *
     * @return array the board
     */
    public function getBoard() {
        return $this->board->getBoard();
    } // end getBoard

    /**
     * Returns the game id
     *
     * @return integer game id
     */
    public function getID() {
        return $this->id;
    } // end getID

    /**
     * Makes a move on the game board
     *
     * @return true if move successful, false otherwise
     */
    public function move($playerId, $square) {
        if ($this->getCurrentPlayer() === $playerId) {
            if ($this->board->move($square)) {
                $square = $this->db->real_escape_string($square);
                $playerId = $this->db->real_escape_string($playerId);
                $query = "
                    INSERT INTO ttt_moves (
                      game_id, 
                      player_id, 
                      ply, 
                      end_location
                    ) 
                    VALUES (
                      '" . $this->id . "',
                      '" . $playerId . "',
                      '" . $this->board->getPly() . "',
                      '" . $square . "'
                    );
                ";
                $result = $this->db->query($query);
                return $result && $this->db->affected_rows() > 0;
            }
        }

        return false;
    } // end move

    /**
     * Returns the game type
     *
     * @return string game type
     */
    public function getGameType() {
        return TicTacToeGame::GAME_TYPE;
    } // end getGameType

    /**
     * Returns the id of player1
     *
     * @return int id of player1
     */
    public function getPlayer1() {
        return $this->player1Id;
    } // end getPlayer1

    /**
     * Returns the id of player2
     *
     * @return int id of player2
     */
    public function getPlayer2() {
        return $this->player2Id;
    } // end getPlayer2

    /**
     * Returns the username of player1
     *
     * @return int username of player1
     */
    public function getPlayer1Username() {
        return $this->player1Username;
    } // end getPlayer1Username

    /**
     * Returns the username of player2
     *
     * @return int username of player2
     */
    public function getPlayer2Username() {
        return $this->player2Username;
    } // end getPlayer2Username

    /**
     * Returns the ply
     *
     * @return int ply
     */
    public function getPly() {
        return $this->board->getPly();
    } // end getPly

    /**
     * Returns the current player id
     *
     * @return int the current player id
     */
    public function getCurrentPlayer() {
        return $this->board->getPly() & 1 ? $this->player2Id : $this->player1Id;
    } // end getCurrentPlayer

    /**
     * Returns the result of the game, if any
     *
     * @return string result
     */
    public function getResult() {
        return $this->result;
    } // end getResult

    /**
     * Returns the time the game ended
     *
     * @return int the end time
     */
    public function getEndTime() {
        return $this->endTime; 
    } // end getEndTime

    /**
     * Returns the time the game started
     *
     * @return int the start time
     */
    public function getStartTime() {
        return $this->startTime; 
    } // end getStartTime

    /**
     * Returns the time the move time limit in seconds
     *
     * @return int the move time limit
     */
    public function getMoveTimeLimit() {
        return $this->moveTimeLimit;
    } // end getMoveTimeLimit

    /**
     * Returns the time the game time limit in seconds
     *
     * @return int the game time limit
     */
    public function getGameTimeLimit() {
        return $this->gameTimeLimit;
    } // end getGameTimeLimit
} // end TicTacToeGame

?>
