<?php

/**
 * Controller to handle requests for destroying seeks
 */
class DeregisterController implements Controller {
    private $userModel;

    /**
     * Couples this controller with its model
     */
    public function __construct() {
        $this->userModel = new User();
    } // end __construct

    /**
     * Executes the controller action
     */
    public function call() {

        // Start a session
        $this->userModel->loadSession();

        // Redirect the user to home if not logged in or post hash is empty
        if (!$this->userModel->loggedIn() || count($_POST) === 0 || !isset($_POST['id'])) {
            header('Location: index.php');
        }
        
        // Attempt removal of specified user if admin
        if ($this->userModel->getPermissions() & User::PERMISSIONS['admin']) {
            echo (new Admin())->deleteUser((int)$_POST['id']);
        }
        
        return false;
    } // end call
} // end DeregisterController

?>
