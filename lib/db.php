<?php

/**
 * A simple DB wrapper class
 */
final class DB {
    private $db;


    /**
     * Constructor method connects to db and returns an instance
     *
     * @param $dbhost the hostname of the database
     * @param $dbuser the username for the database connection
     * @param $dbpass the password for the database
     * @param $database the name of the database
     * @return a DB instance
     */
    public function __construct($dbhost, $dbuser, $dbpass, $database) {
        $this->db = new mysqli($dbhost, $dbuser, $dbpass, $database);
        unset($dbhost, $dbuser, $dbpass, $database);
    }

    /**
     * Runs a query on the database
     *
     * @param $query the SQL query to run on the database
     * @return the result of the query
     */
    public function query($query) {
        return $this->db->query($query);
    }

    /**
     * Closes the connection
     *
     * @return the result of the closure
     */
    public function close() {
        return $this->db->close();
    }

    /**
     * Escapes a string
     *
     * @param $str the string to escape
     * @return the result of the procedure
     */
    public function real_escape_string($str) {
        return $this->db->real_escape_string($str);
    }

    /**
     * Prepares a database query
     *
     * @param $query the database query string
     * @return a statement object
     */
    public function prepare($query) {
        return $this->db->prepare($query);
    }

    /**
     * Returns a count of affected rows for the previous query
     *
     * @return integer number of affected rows in the last query
     */
    public function affected_rows() {
        return mysqli_affected_rows($this->db);
    }
}

?>
