<?php

/**
 * Controller to handle requests for logins
 */
class LoginController implements Controller {
    private $model;

    /**
     * Couples this controller with its model
     */
    public function __construct() {
        $this->model = new User();
    } // end __construct

    /**
     * Executes the controller action
     */
    public function call() {
        $data = json_decode(file_get_contents('php://input'), true);

        // Redirect the user to home if data hash is incomplete
        if (count($data) < 2) {
            header('Location: index.php');
        }

        // Start a session
        $user = $this->model;
        $user->loadSession();

        if ($user->loggedIn()) {
            $user->logout();
        }

        $errors = [];
        
        if (!isset($data["username"]) || strlen($data["username"]) === 0) {
            $errors[]= "username required";
        }
        
        if (!isset($data["password"]) || strlen($data["password"]) === 0) {
            $errors[]= "password required";
        }
        
        if (count($errors) === 0 && !$user->login($data["username"], $data["password"])) {
            $errors[]= "invalid username or password";
        }

        return json_encode([ "errors" => $errors ]);
    } // end call
} // end LoginController

?>
