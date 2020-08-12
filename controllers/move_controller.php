<?php

/**
 * Controller to handle requests for new seeks
 */
class MoveController implements Controller {
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

        // Validate login and post data and attempt move
        if ($this->userModel->loggedIn() && count($_POST) >= 2 && 
            isset($_POST['game_id']) && isset($_POST['square'])) {
            
            // Make game object by id and apply move to it
            $gameId = (int)$_POST['game_id'];
            $destSquare = (int)$_POST['square'];
            $game = $this->userModel->getGameById($gameId);
            
            if ($game->move($this->userModel->getId(), $destSquare)) {
            
                // Set the result of the game if applicable
                $game->setResult();

                header('Content-Type: application/json; charset=UTF-8');
                return json_encode([
                    'errors' => [],
                    'gameId' => $gameId,
                    'destSquare' => $destSquare,
                    'result' => $game->getResult(),
                    'board' => $game->getBoard(),
                    'endTime' => date('Y/m/d h:i A', $game->getEndTime())
                ]);
            }
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode([ 'errors' => ['move failed'] ]);
    } // end call
} // end MoveController

?>
