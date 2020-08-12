<?php

/**
 * Model for a Tic Tac Toe game seek
 *
 * TODO: not used
 */
class TicTacToeSeek {
    private const TABLE_NAME = 'ttt_seeks';
    private $db;
    private $userId;

    /**
     * Constructor for a TicTacToeSeek object
     */
    public function __construct($userId) {
        $this->db = new DB(DBHOST, DBUSER, DBPASS, DATABASE);
        $this->userId = $this->db->real_escape_string($userId);
    } // end __construct

    /**
     * Getter for seeks excluding those from the current user
     *
     * @return array of seeks
     */
    public function getSeeks() {
        $query = "SELECT * FROM " . TicTacToeSeek::TABLE_NAME .
                  " WHERE username != '$this->userId';";
        $result = $this->db->query($query);

        if ($result && $result->num_rows >= 1) {
            $seeks = [];

            while ($row = $result->fetch_object()) {
                $seeks[]= [ 
                    "id" => $row->id,
                    "user_id" => $row->user_id,
                    "timestamp" => $row->timestamp
                ];
            }

            return $seeks;
        }

        return false;
    } // end joinSeek

    /**
     * Creates a new game seek
     *
     * @return int id of the seek creator
     */
    public function getUser() {
        return $this->player;
    } // end createSeek

    /**
     * Creates a new game seek
     *
     * @return true if seek was successfully created, false otherwise
     */
    public function createSeek() {
        $this->player = $this->db->real_escape_string($player);


    } // end createSeek

    /**
     * Joins an existing seek, destroying the seek and creating a new game
     *
     * @return true if seek was successfully destroyed and game was created, false otherwise
     */
    public function joinSeek($joinUserId) {
        $joinUserId = $this->db->real_escape_string($joinUserId);

    } // end joinSeek
} // end TicTacToeGame

?>
