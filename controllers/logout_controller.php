<?php

/**
 * Controller to handle logout requests
 */
class LogoutController implements Controller {
    private $model;

    /**
     * Couples this controller with its model
     */
    public function __construct() {

        // Populate this model with a user object
        $this->model = new User();
    } // end __construct

    /**
     * Executes the controller action
     */
    public function call() {

        // Start a session
        $this->model->loadSession();

        if ($this->model->loggedIn()) {
            $this->model->logout();
        }
        
        header('Location: index.php');
    } // end call
} // end LogoutController

?>
