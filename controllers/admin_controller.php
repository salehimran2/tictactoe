<?php

/**
 * Controller to handle requests for the home page
 */
class AdminController implements Controller {
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
        include LAYOUTS . 'header.php';
        include LAYOUTS . 'title.php';

        // Start a session
        $this->userModel->loadSession();

        // Determine whether user is logged in
        $loggedIn = $this->userModel->loggedIn();

        if ($loggedIn) {
            $username = $this->userModel->getUsername();
            $permissions = $this->userModel->getPermissions();
            $admin = $permissions & User::PERMISSIONS['admin'];

            // Prevent non-admins from accessing this page
            if (!$admin) {
                header("Location: index.php");
                exit;
            }

            include LAYOUTS . 'navigation.php';
            include LAYOUTS . 'content_start.php';
            
            // Retrieve and display users
            $dashboard = new Admin();
            $users = $dashboard->getUsers();
            include VIEWS . 'admin/format_users.php';
            echo formatUsers($users);

            include LAYOUTS . 'content_end.php';
        }
        else {
            header("Location: index.php");
            exit;
        }

        include LAYOUTS . 'footer.php';
        include VIEWS . 'helpers/ajax.php';
        include VIEWS . 'admin/admin_script.php';
        include LAYOUTS . 'end.php';
    } // end call
} // end AdminController

?>
