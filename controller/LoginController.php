<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 20:23
 */

namespace controller;

class LoginController {

// Init variables
    private $users;
    private $AppController;

    public $errorModel;
    public $formView;
    public $pageViewObj;


// Constructor
    public function __construct($AppController) {

        // Store Application controller reference
        $this->AppController = $AppController;

        // Create users model
        $this->users = new \model\Users();

        // Create form view object
        $this->formView = new \view\FormView($this->users);

        // Process Login
        $this->ProcessLogin();

    }

// Public methods
    public function ProcessLogin() {

        // Try to authenticate
        try {

            // If user wants to login
            if($this->formView->UserWantsToLogin()) {

                // Get login attempt
                $loginAttemptArray = $this->formView->GetLoginAttempt();

                // Create new user from login attempt
                $loginAttemptUser = new \model\User(NULL, $loginAttemptArray['username'], $loginAttemptArray['password'], false);

                // Try to authenticate user
                if($this->users->Authenticate($loginAttemptUser)) {

                    // Store logged in user object in sessions cookie
                    \model\Users::StoreLoginInSessionCookie($loginAttemptUser);

                    // If the user authenticated successfully
                    return true;

                } else {

                    // If the user was denied access
                    throw new \Exception("Wrong name or password");
                }
            }

        } catch (\Exception $exception) {

            // Store error in application errors
            $this->AppController->errorModel->AddError($exception);
        }

        // Return login failure
        return false;
    }

    public static function Logout() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy session cookie
        session_destroy();
    }

    public function GetOutput(){
        return $this->formView->GetOutput();
    }

// Private methods


} 