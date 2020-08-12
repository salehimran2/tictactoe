<?php

/**
 * Seeks model
 */
final class Seeks {
    public const TABLE_NAME = 'ttt_seeks';

    private $db;

    /**
     * Constructor function for a Seeks model
     */
    public function __construct() {
        $this->db = new DB(DBHOST, DBUSER, DBPASS, DATABASE);
    } // end __construct

    /**
     * Returns a list of active seeks
     *
     * @return a list of active seeks or false on query failure
     */
    public function getSeeks() {
        $query = '
            SELECT 
              ' . Seeks::TABLE_NAME . '.id
             ,' . Seeks::TABLE_NAME . '.user_id
             ,' . Seeks::TABLE_NAME . '.timestamp
             ,' . User::TABLE_NAME . '.username
            FROM ' . Seeks::TABLE_NAME . ' 
            INNER JOIN ' . User::TABLE_NAME .  ' 
            ON ' . Seeks::TABLE_NAME . '.user_id = ' . User::TABLE_NAME . '.id 
            ORDER BY ' . Seeks::TABLE_NAME . '.timestamp DESC;
        ';
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            $seeks = [];

            while ($row = $result->fetch_object()) {
                $seeks[]= [
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'timestamp' => date('Y/m/d h:i A', $row->timestamp),
                    'username' => $row->username
                ];
            }

            return $seeks;
        }

        return false;
    } // end getSeeks

    /**
     * Get the owner id of a seek
     *
     * @param $seekId the integer id of a seek
     * @return integer user id if found, false otherwise
     */
    public function getSeekOwner($seekId) {
        $this->db->real_escape_string($seekId);
        $query = '
            SELECT user_id FROM ' . Seeks::TABLE_NAME . ' 
            WHERE id = ' . $seekId . ';'
        ;
        $result = $this->db->query($query);

        if ($result && $result->num_rows === 1 &&
            $row = $result->fetch_object()) {
            return (int)$row->user_id;
        }

        return false;
    } // end getSeekOwner

    /**
     * Turns a seek into a game
     *
     * @param $user the user joining the seek
     * @return true if successful, false otherwise
     */
    public function joinSeek($seekId, $user) {
        $ownerId = $this->getSeekOwner($seekId);

        // Prevent user playing themself
        if ($ownerId === $user->getId()) {
            return false;
        }

        // Randomize sides
        if (mt_rand(0, 1)) {
            $id1 = $user->getId();
            $id2 = $ownerId;
        }
        else {
            $id1 = $ownerId;
            $id2 = $user->getId();
        }

        // Try removing the seek and creating a game
        if ($this->removeSeekByUserId($seekId, $ownerId)) {
            $id1 = $this->db->real_escape_string($id1);
            $id2 = $this->db->real_escape_string($id2);
            $query = '
                INSERT INTO ' . TicTacToeGame::TABLE_NAME . ' 
                    (player1_id, player2_id, start_time, ply)
                VALUES 
                    (' . $id1 . ', ' . $id2 . ', ' . time() . ', 0);'
            ;
            $result = $this->db->query($query);
            return $result && $this->db->affected_rows() > 0;
        }

        return false;
    } // end joinSeek

    /**
     * Creates a new seek
     *
     * @param $userId the id of the player initiating the seek
     * @return true if successful, false otherwise
     */
    public function newSeek($userId) {
        $userId = $this->db->real_escape_string($userId);
        $query = '
            INSERT INTO ' . Seeks::TABLE_NAME . ' (user_id, timestamp)
            VALUES (' . $userId . ', ' . time() . ');'
        ;
        $result = $this->db->query($query);
        return $result && $this->db->affected_rows() > 0;
    } // end newSeek

    /**
     * Removes a seek by id
     *
     * @param $seekId the id of the seek to remove
     * @param $user the user initiating the removal (admin or seek owner)
     * @return true if successful, false otherwise
     */
    public function removeSeek($seekId, $user) {
        $seekId = $this->db->real_escape_string($seekId);

        if ($user->getPermissions() & User::PERMISSIONS['admin']) {
            $query = '
                DELETE FROM ' . Seeks::TABLE_NAME . ' 
                WHERE id = ' . $seekId . ';'
            ;
            $result = $this->db->query($query);
            return $result && $this->db->affected_rows() > 0;
        }
        
        return $this->removeSeekByUserId($seekId, $user->getId());
    } // end removeSeek

    /**
     * Removes a seek by seek id and user id without an admin option
     *
     * @param $seekId the id of the seek to remove
     * @param $userId the seek owner id initiating the removal
     * @return true if successful, false otherwise
     */
    public function removeSeekByUserId($seekId, $userId) {
        $seekId = $this->db->real_escape_string($seekId);
        $userId = $this->db->real_escape_string($userId);
        $query = '
            DELETE FROM ' . Seeks::TABLE_NAME . ' 
            WHERE id = ' . $seekId . ' 
            AND user_id = ' . $userId . ';'
        ;
        $result = $this->db->query($query);
        return $result && $this->db->affected_rows() > 0;
    } // end removeSeekByUserId
} // end Seeks

?>
