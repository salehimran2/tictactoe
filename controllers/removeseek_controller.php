<?php

/**
 * Controller to handle requests for destroying seeks
 */
class RemoveSeekController implements Controller {
    private $userModel;

    /**
     * Couples this controller with its model
     */
    public function __construct() {

        // Populate this model with a user object
        $this->userModel = new User();
    } // end __construct

    /**
     * Executes the controller action
     */
    public function call() {

        // Start a session
        $this->userModel->loadSession();

        // Determine whether user is logged in
        $loggedIn = $this->userModel->loggedIn();

        // Redirect the user to home if not logged in
        if (!$loggedIn || !isset($_POST) || count($_POST) === 0) {
            header("Location: index.php");
        }

        // Attempt removal of specified seek
        echo (new Seeks())->removeSeek((int)$_POST["id"], $this->userModel);
    } // end call
} // end RemoveSeekController

?>
