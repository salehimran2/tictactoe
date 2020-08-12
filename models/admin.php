<?php

/**
 * Dashboard model for administrators
 */
class Admin {
    private $db;

    /**
     * Constructor for an Admin
     *
     * @return an Admin instance
     */
    public function __construct() {
        $this->db = new DB(DBHOST, DBUSER, DBPASS, DATABASE);
    } // end __construct

    /**
     * Retrieves a list of users
     *
     * @return an array of id/username pairs or false on query failure
     */
    public function getUsers() {
        $query = '
            SELECT id, username, permissions FROM ' . User::TABLE_NAME . '
            ;
        '; 
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $users = [];

            while ($userData = $result->fetch_object()) {
                $users[]= [ 
                    'id' => $userData->id, 
                    'username' => $userData->username, 
                    'permissions' => $userData->permissions,
                    'admin' => $userData->permissions & User::PERMISSIONS['admin']
                ];
            }

            return $users;
        }

        return false;
    } // end getUsers

    /**
     * Removes a user and all their associated games and content from the database
     *
     * @return true if removal successful, false otherwise
     */
    public function deleteUser($userId) {
        $userId = $this->db->real_escape_string($userId);
        $query = '
            DELETE FROM ' . User::TABLE_NAME . '
            WHERE id = ' . $userId . '
            ;
        '; 
        $result = $this->db->query($query);
        return $result && $result->num_rows === 1;
    } // end deleteUser

    // TODO
    private function changePermissions($targetUsername, $newPermissions) { }
} // end Admin

?>
