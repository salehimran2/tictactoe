<?php

/**
 * Controller to handle requests for new seeks
 */
class NewSeekController implements Controller {
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

        // Redirect the user to home if not logged in
        if (!$this->userModel->loggedIn()) {
            header("Location: index.php");
        }

        $admin = $this->userModel->getPermissions() & User::PERMISSIONS['admin'];

        // Attempt adding the new seek
        $seek = new Seeks();

        if ($seek->newSeek($this->userModel->getId())) {
            return json_encode([
                "userId" => $this->userModel->getId(),
                "admin" => $admin > 0,
                "seeks" => $seek->getSeeks()
            ]);
        }

        return false;
    } // end call
} // end NewSeekController

?>
