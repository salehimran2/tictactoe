<?php

/**
 * Contains handlers for persisting a session via database
 */
final class Session {
    private const TABLE_NAME = "sessions";
    private $db;


    /**
     * Constructs a new Session
     *
     * @param $dbhost the hostname of the database
     * @param $dbuser the username for the database connection
     * @param $dbpass the password for the database
     * @param $database the name of the database
     * @return a Session instance
     */
    public function __construct($dbhost, $dbuser, $dbpass, $database) {

        // Override default session handlers with member functions
        session_set_save_handler(
            [&$this, "open"], [&$this, "close"], 
            [&$this, "read"], [&$this, "write"], 
            [&$this, "destroy"], [&$this, "gc"]
        );

        // Connect to the DB
        $this->db = new DB($dbhost, $dbuser, $dbpass, $database);
        //unset($dbhost, $dbuser, $dbpass, $database);
        
        // Set session configuration
        // TODO -- is this working and the right values? Needs cleanup
        //ini_set("session.save_path", $session_save_path);
        //unset($session_save_path);
        //ini_set("session.gc_maxlifetime", 86400); 

        // Enable session garbage collection with a 1% chance of
        // running on each session_start()
        ini_set("session.gc_probability", 1);
        ini_set("session.gc_divisor", 100);

        // Start session if not already running
        if (!isset($_SESSION)) {
            session_start();
        }
    } // end __construct

    /**
     * Handler for opening a session
     *
     * @return boolean
     */
    public function open() { 
        return $this->db ? true : false; 
    } // end open

    /**
     * Handler for closing a session
     *
     * @return boolean
     */
    public function close() { 
        return $this->db->close();
    } // end close

    /**
     * Handler for reading session data
     *
     * @param $id session id
     * @return string result on success or empty string on failure
     */
    public function read($id) {
        $id = $this->db->real_escape_string($id);
        $result = $this->db->query("SELECT data FROM " . Session::TABLE_NAME . " WHERE id = '$id';");
        
        if (isset($result) && $result && $result->num_rows === 1) {
            return $result->fetch_object()->data;
        }
       
        return "";
    } // end read

    /**
     * Handler for writing session data
     *
     * @param $id session id
     * @param $data the data to store
     * @return boolean
     */
    public function write($id, $data) {
        $expire = time();
        $query = "REPLACE INTO " . Session::TABLE_NAME . " VALUES (?, ?, ?);";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sss", $id, $expire, $data);

        if ($statement->execute()) {
          $statement->close();
          return true;
        }

        return false;
    } // end write

    /**
     * Handler for destroying a session
     *
     * @param $id session id
     * @return boolean
     */
    public function destroy($id) {
        $id = $this->db->real_escape_string($id);
        return $this->db->query("DELETE FROM " . Session::TABLE_NAME . " WHERE id = '$id';");
    } // end destroy

    /**
     * Session garbage collector
     *
     * @param $max the maximum gc time
     * @return boolean
     */
    public function gc($max) {
        $old = time() - $max;
        return $this->db->query("DELETE FROM " . Session::TABLE_NAME . " WHERE 'access < $old';");
    } // end gc
} // end Session

?>
