<?php

/**
 * Registration model
 */
final class Registration {
    private $db;

    /**
     * Constructor for Register model
     *
     * @return Register instance
     */
    public function __construct() { 
        $this->db = new DB(DBHOST, DBUSER, DBPASS, DATABASE);
    } // end __construct

    /**
     * Registers a new user
     *
     * @param $username the user's unique username
     * @param $password the user's password
     * @param $email the user's email
     * @param $permissions the permissions settings for this user
     * @return true if the user was created successfully, false otherwise
     */
    public function register($username, $password, $email, $permissions = 0) {
        if (!$this->validate($username, $password, $email)) { 
            return false; 
        }

        $username = $this->db->real_escape_string($username);
        $email = $this->db->real_escape_string($email);
        $permissions = $this->db->real_escape_string($permissions);
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $query = '
            INSERT INTO ' . User::TABLE_NAME . ' (
              username,
              email,
              password,
              permissions
            ) VALUES (?, ?, ?, ?)
            ;
        ';
        $statement = $this->db->prepare($query);
        $statement->bind_param('ssss', $username, $email, $hash, $permissions);

        if ($statement->execute()) {

            // TODO make this a transaction http://php.net/manual/en/mysqli.begin-transaction.php
            $user = new User();
            $user->login($username, $password);

            if ((new Stats($user->getId()))->addUser()) {
                $statement->close();
                return true;
            }
        }

        $statement->close();
        return false;
    } // end register

    /**
     * Validates all registration information
     *
     * @param $username the user's unique username
     * @param $password the user's password
     * @param $email the user's email
     * @return true if the user's information is valid, false if invalid
     */
    public function validate($username, $password, $email) {
        return 
            $this->validateUsernameUniqueness($username) &&
            $this->validateUsernameCharacters($username) &&
            $this->validateUsernameLength($username) &&
            $this->validatePasswordLength($password) &&
            $this->validatePasswordCharacters($password) && 
            $this->validateEmail($email)
        ;
    } // end validate

    /**
     * Validates username uniqueness
     *
     * @param $username the username to test for uniqueness
     * @return true if the username is unqiue , false otherwise
     */
    public function validateUsernameUniqueness($username) {
        $username = $this->db->real_escape_string($username);
        $query = '
            SELECT * FROM ' . User::TABLE_NAME . "
            WHERE username = '$username'
            ;
        ";
        $result = $this->db->query($query);
        return $result && $result->num_rows === 0;
    } // end validateUniqueUsername

    /**
     * Validates username characters
     *
     * @param $username the username to test 
     * @return true if the username contains valid characters, false otherwise
     */
    public function validateUsernameCharacters($username) {
        return preg_match('/^[A-Za-z0-9_-]+$/', $username);
    } // end validateUsernameCharacters

    /**
     * Validates username length
     *
     * @param $username the username to test 
     * @return true if the username passes the length requirement, false otherwise
     */
    public function validateUsernameLength($username) {
        return strlen($username) <= 20 && strlen($username) >= 4;
    } // end validateUsernameLength

    /**
     * Validates password length
     *
     * @param $password the password to test 
     * @return true if the password passes the length requirement, false otherwise
     */
    public function validatePasswordLength($password) {
        return strlen($password) >= 4 && strlen($password) <= 20; // TODO temporarily short
    } // end validatePasswordLength

    /**
     * Validates password characters
     *
     * @param $password the password to test
     * @return true if the password contains valid characters, false otherwise
     */
    public function validatePasswordCharacters($password) {
        return true; //preg_match('/^[A-Za-z0-9!@#$%^&*()]{4,}$/', $username); // TODO
    } // end validateUsernameCharacters

    /**
     * Validates email address
     *
     * @param $email the email to test
     * @return true if the email is valid, false otherwise
     */
    public function validateEmail($email) {
        return $email === filter_var($email, FILTER_SANITIZE_EMAIL) && 
               filter_var($email, FILTER_VALIDATE_EMAIL);
    } // end validateEmail
} // end Register

?>
