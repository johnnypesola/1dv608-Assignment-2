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

            // If user is logged in and wants to logout
            if($this->formView->UserWantsToLogout() && \model\Cookies::IsUserLoggedIn()) {
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
                    \model\Cookies::KeepUserLoggedIn($loginAttemptUser);

                    // Set a login message to be displayed for the user.
                    $this->formView->SetLoggedInMessage();

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

    public function Logout() {

        // Assert that user is logged in
        assert(\model\Cookies::IsUserLoggedIn());

        // Only logout user if logged in
        if(\model\Cookies::IsUserLoggedIn()) {

            // Start session if its not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Set a logout message to be displayed for the user.
            $this->formView->SetLoggedOutMessage();

            // Clear user login
            \model\Cookies::ForgetUserLoggedIn();

            // The user logged out successfully, reload page
            $this->appController->ReloadPage();
        }
    }

    public function GetOutput(){
        return $this->formView->GetHTML();
    }

// Private methods


} 