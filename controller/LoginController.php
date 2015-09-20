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
    private $appController;
    public $formView;

// Constructor
    public function __construct($appController) {

        // Store Application controller reference
        $this->appController = $appController;

        // Create users model
        $this->users = new \model\Users();

        // Create form view object
        $this->formView = new \view\FormView($this->users, $this->appController->exceptions);

        // Process Login
        $this->ProcessLogin();

    }

// Public methods
    public function ProcessLogin() {

        // Try to authenticate
        try {

            // If user wants to logout
            if($this->formView->UserWantsToLogout()) {
                $this->Logout();
            }

            // If user wants to login
            else if($this->formView->UserWantsToLogin()) {

                // Get login attempt
                $loginAttemptArray = $this->formView->GetLoginAttempt();

                // Create new user from login attempt
                $loginAttemptUser = new \model\User(NULL, $loginAttemptArray['username'], $loginAttemptArray['password'], false);

                // Try to authenticate user
                if($this->users->Authenticate($loginAttemptUser)) {

                    // Store logged in user object in sessions cookie
                    $this->users->StoreLoginInSessionCookie($loginAttemptUser);

                    // The user authenticated successfully, reload page
                    $this->appController->ReloadPage();

                } else {

                    // The user was denied access
                    throw new \Exception("Wrong name or password");
                }
            }

        } catch (\Exception $exception) {

            // Store exceptions in applications exceptions container model
            $this->appController->exceptions->AddException($exception);
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
        return $this->formView->GetHTML();
    }

// Private methods


} 