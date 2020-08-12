<?php

/**
 * Controller to handle requests for destroying seeks
 */
class GetSeeksController implements Controller {
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

        // Determine whether user is logged in
        $loggedIn = $this->userModel->loggedIn();


        // Redirect the user to home if not logged in
        if (!$loggedIn) {
            header("Location: index.php");
        }

        $admin = $this->userModel->getPermissions() & User::PERMISSIONS['admin'];

        // Return a list of seeks
        $seek = new Seeks();
        return json_encode([
            "userId" => $this->userModel->getId(),
            "admin" => $admin > 0,
            "seeks" => $seek->getSeeks()
        ]);
    } // end call
} // end RemoveSeekController

?>
