<?php

/**
 * Controller to handle requests for joining a seek
 */
class JoinSeekController implements Controller {
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

        // Redirect user if not logged in or post data unavailable
        if (!$this->userModel->loggedIn() || count($_POST) === 0 || !isset($_POST['id'])) {
            header('Location: index.php');
        }

        // Join specified seek if possible
        $seek = new Seeks();
        echo $seek->joinSeek($_POST['id'], $this->userModel);
    } // end call
} // end JoinSeekController

?>
