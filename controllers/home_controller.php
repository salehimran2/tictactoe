<?php

/**
 * Controller to handle requests for the home page
 */
class HomeController implements Controller {
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

            include LAYOUTS . 'navigation.php';
            include LAYOUTS . 'content_start.php';

            // Retrieve list of current games
            $games = $this->userModel->getCurrentGames();

            if ($games && count($games) > 0) {

                // Render view for each game
                include VIEWS . 'ttt/ttt_board_grid_header.php';

                foreach ($games as $game) {
                    showGame($username, $game);
                }

                include VIEWS . 'ttt/ttt_board_grid_footer.php';
            }
            else {
                include VIEWS . 'ttt/ttt_board_empty.php';
            }

            include LAYOUTS . 'content_end.php';
            include LAYOUTS . 'footer.php';
            include VIEWS . 'helpers/ajax.php';
            include VIEWS . 'ttt/ttt_script.php';
            include LAYOUTS . 'end.php';
        }
        else {
            include VIEWS . 'home/site_description.php';
            include VIEWS . 'home/entryway.php';
            include VIEWS . 'helpers/ajax.php';
            include VIEWS . 'home/entryway_script.php';
            include LAYOUTS . 'end.php';
        }
    } // end call
} // end HomeController

?>
