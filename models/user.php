<?php

/**
 * Represents a user
 */
class User {
    public const TABLE_NAME = 'ttt_users';
    public const PERMISSIONS = [
        "user"  => 1 << 0, // TODO new users will default to 0 but after confirming email will be set to permission 1
        "admin" => 1 << 7
    ];

    private $id;
    private $username;
    private $email;
    private $password;
    private $permissions;
    private $session;
    private $db;

    /**
     * Constructor for a User
     *
     * @param $dbhost the hostname of the database
     * @param $dbuser the username for the database connection
     * @param $dbpass the password for the database
     * @param $database the name of the database
     * @return a User instance
     */
    public function __construct() {
        $this->session = new Session(DBHOST, DBUSER, DBPASS, DATABASE);
        $this->db = new db(DBHOST, DBUSER, DBPASS, DATABASE);
    } // end __construct

    /**
     * Getter for id
     *
     * @return int $id
     */
    public function getId() {
        return $this->id;
    } // end getId

    /**
     * Getter for username
     *
     * @return string $username
     */
    public function getUsername() {
        return $this->username;
    } // end getUsername

    /**
     * Getter for permissions
     *
     * @return int permissions
     */
    public function getPermissions() {
        return $this->permissions;
    } // end GetPermissions

    /**
     * Returns whether this user is logged in
     *
     * @return true if logged in, false otherwise
     */
    public function loggedIn() {
        return isset($_SESSION["User"]) && $_SESSION["User"] === $this->username;
    } // end loggedIn

    /**
     * Load a session for this user if possible
     *
     * @return true if session is active, false otherwise
     */
    public function loadSession() {
        if (isset($_SESSION["User"])) {

            // Populate instance data with db query using session username
            $username = $this->db->real_escape_string($_SESSION["User"]);
            $query = '
                SELECT * FROM ' . User::TABLE_NAME . " 
                WHERE username = '$username';"
            ;
            $result = $this->db->query($query);

            if ($result && $result->num_rows === 1 && 
                $userData = $result->fetch_object()) {

                $this->id = (int)$userData->id;
                $this->username = $userData->username;
                $this->email = $userData->email;
                $this->password = $userData->password;
                $this->permissions = (int)$userData->permissions;

                return true; 
            }
        }

        return false;
    } // end loadSession

    /**
     * Logs in a user, saving their username in a $_SESSION variable
     *
     * @param $username the user's unique username
     * @param $password the user's password
     * @return true if the user was successfully logged in, false otherwise
     */
    public function login($username, $password) {
        $username = $this->db->real_escape_string($username);
        $query = '
            SELECT * FROM ' . User::TABLE_NAME . " 
            WHERE username = '$username';"
        ;
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows === 1 && 
            $userData = $result->fetch_object()) {

            if (isset($userData->password) && 
                password_verify($password, $userData->password)) {

                $this->id = $userData->id;
                $this->username = $userData->username;
                $this->email = $userData->email;
                $this->password = $userData->password;
                $this->permissions = $userData->permissions;

                $_SESSION["User"] = $this->username;
                return true;
            }
        }

        return false;
    } // end login

    /**
     * Logs this user out
     *
     * @return true if the user was successfully logged out, false otherwise
     */
    public function logout() {
        $result = session_destroy();
        unset($_SESSION[$this->username]);
        return $result;
    } // end logout

    /**
     * Registers a new user
     *
     * @param $username the user's unique username
     * @param $password the user's password
     * @param $email the user's email
     * @param $permissions the permissions settings for this user
     * @return true if the user was created successfully, false otherwise
     */
    //public function register($username, $password, $email, $permissions = 0) {
    //    $username = $this->db->real_escape_string($username);
    //    $email = $this->db->real_escape_string($email);
    //    $permissions = $this->db->real_escape_string($permissions);
    //    $hash = password_hash($password, PASSWORD_BCRYPT);
    //    //unset($password);
    //    $query = 'INSERT INTO ' . User::TABLE_NAME . ' (
    //                username,
    //                email,
    //                password,
    //                permissions
    //              ) VALUES (?, ?, ?, ?);';
    //    $statement = $this->db->prepare($query);
    //    $statement->bind_param('ssss', $username, $email, $hash, $permissions);
    //
    //    if ($statement->execute() && $this->login($username, $password)) {
    //
    //        // TODO make this a transaction? http://php.net/manual/en/mysqli.begin-transaction.php
    //        if (Stats::addUser($this->id)) {
    //            $statement->close();
    //            return true;
    //        }
    //    }
    //
    //    $statement->close();
    //    return false;
    //} // end register

    /**
     * Retrieves a game by id
     *
     * @return Game object
     */
    public function getGameById($id) {
        $query = '
            SELECT * FROM ' . TicTacToeGame::TABLE_NAME . " 
            WHERE id = '$id'
            ;
        ";
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows === 1) {
            return new TicTacToeGame($result->fetch_object());
        }

        return false;
    } // end getGameById

    /**
     * Retrieves a list of completed games for this user
     *
     * @return an array of Game objects
     */
    public function getCompletedGames() {
        $query = '
            SELECT * FROM ' . TicTacToeGame::TABLE_NAME . " 
            WHERE '$this->id' IN (player1_id, player2_id)
            AND result > ''
            ORDER BY end_time DESC
            ;
        "; 
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $games = [];

            while ($gameData = $result->fetch_object()) {
                $games[]= new TicTacToeGame($gameData);
            }

            return $games;
        }

        return false;
    } // end getCompletedGames

    /**
     * Retrieves a list of current games for this user
     *
     * @return an array of Game objects
     */
    public function getCurrentGames() {
        $query = '
            SELECT * FROM ' . TicTacToeGame::TABLE_NAME . " 
            WHERE $this->id IN (player1_id, player2_id)
            AND RESULT IS NULL OR RESULT = '' 
            ORDER BY start_time DESC
            ;
        ";
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $games = [];

            while ($gameData = $result->fetch_object()) {
                $games[]= new TicTacToeGame($gameData);
            }

            return $games;
        }

        return false;
    } // end getCurrentGames

    /**
     * Removes a user and all their associated games and content from the database
     *
     * @param $password
     * @return true if removal successful, false otherwise
     */
    public function unregister($password) {
        if (password_verify($password, $this->password)) {
            $admin = new Admin();
            return $admin->deleteUser($this->id);
        }

        return false;
    } // end unregister

    // TODO
    public function changeUsername($password, $newUsername) { }
    public function changePassword($password, $newPassword) { }
    public function changeEmail($password, $email) { }
} // end User

?>
