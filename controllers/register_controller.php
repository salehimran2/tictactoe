<?php

/**
 * Controller to handle requests for registration
 */
class RegistrationController implements Controller {
    private $userModel;
    private $regModel;

    /**
     * Couples this controller with model
     */
    public function __construct() {
        $this->userModel = new User();
        $this->regModel = new Registration();
    } // end __construct

    /**
     * Executes the controller action
     */
    public function call() {
        $data = json_decode(file_get_contents('php://input'), true);

        // Redirect the user to home if data hash is empty
        if (count($data) < 4) {
            header('Location: index.php');
        }

        $form = array_map('trim', $data);
        $errors = [];

        if (!isset($form['username']) || strlen($form['username']) === 0) {
            $errors[]= "username required";
        }
        else {
            if (!$this->regModel->validateUsernameCharacters($form['username'])) {
                $errors[]= "username can only contain letters, numbers, underscores and dashes";
            }
            
            if (!$this->regModel->validateUsernameLength($form['username'])) {
                $errors[]= "username length must be between 4 and 20 characters";
            }
            
            if (!$this->regModel->validateUsernameUniqueness($form['username'])) {
                $errors[]= "username is already taken";
            }
        }

        if (!isset($form['password']) || strlen($form['password']) === 0) {
            $errors[]= "password required";
        }
        else {
            if (!isset($form['passwordVerify'])) {
                $errors[]= "password verification required";
            }
            else if (!$this->regModel->validatePasswordLength($form['password'])) {
                $errors[]= "password must be between 8 and 20 characters";
            }
            else if ($form['password'] !== $form['passwordVerify']) {
                $errors[]= "passwords must match";
            }
            // TODO validate pw characters
        }

        if (!isset($form['email']) || strlen($form['email']) === 0) {
            $errors[]= "email required";
        }
        else {
            if (!isset($form['emailVerify'])) {
                $errors[]= "email verification required";
            }
            else if ($form['email'] !== $form['emailVerify']) {
                $errors[]= "emails must match";
            }
            else if (!$this->regModel->validateEmail($form['email'])) {
                $errors[]= "invalid email address provided";
            }
        }

        if (count($errors) === 0 && 
            $this->regModel->register($form['username'], $form['password'], $form['email'])) { 
            $user = new User();
            $user->loadSession();

            // TODO send confirmation email with unqiue hash as part of tx (from model?)
        }

        return json_encode([ "errors" => $errors ]);
    } // end call
} // end RegisterController

?>
