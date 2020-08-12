<?php

/**
 * Controller for seeks
 */
class SeeksController implements Controller {
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
        if (!$loggedIn) {
            header("Location: index.php");
        }

        $username = $this->userModel->getUsername();
        $userId = $this->userModel->getId();
        $permissions = $this->userModel->getPermissions();
        $admin = $this->userModel->getPermissions() & User::PERMISSIONS['admin'];
        include LAYOUTS . 'header.php';
        include LAYOUTS . 'title.php';
        include LAYOUTS . 'navigation.php';
        include LAYOUTS . 'content_start.php';

        $seekModel = new Seeks();
        $seeks = $seekModel->getSeeks();

        // Show link to make new seek and list of available seeks
        include VIEWS . 'seeks/new_seek.php';
        include LAYOUTS . 'content_end.php';
        include LAYOUTS . 'footer.php';
        include VIEWS . 'helpers/ajax.php';
        include VIEWS . 'seeks/seeks_script.php';
        include LAYOUTS . 'end.php';
    } // end call
} // end SeeksController

?>
