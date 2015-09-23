<?php

namespace controller;

class LoginController {

// Init variables
    private $users;
    private $auth;
    private $appController;
    public $formView;

// Constructor
    public function __construct($appController) {

        // Store Application controller reference
        $this->appController = $appController;

        // Create users model
        $this->users = new \model\Users();

        // Create auth model
        $this->auth = new \model\Auth($this->users);

        // Create form view object
        $this->formView = new \view\FormView($this->users, $this->auth, $this->appController->exceptions);

        // If session is not hijacked
        if(!$this->auth->isSessionHijacked()) {

            // Process Actions
            $this->ProcessActions();
        }
    }

// Public methods
    public function ProcessActions() {

        // Try to authenticate
        try {

            // If user is logged in and wants to logout
            if($this->formView->UserWantsToLogout() && $this->auth->IsUserLoggedIn()) {
                $this->Logout();
            }

            // If user wants to login
            else if($this->formView->UserWantsToLogin() && !$this->auth->IsUserLoggedIn()) {

                // Get login attempt
                $loginAttemptArray = $this->formView->GetLoginAttempt();

                // Create new user from login attempt
                $loginAttemptUser = new \model\User(NULL, $loginAttemptArray['username'], $loginAttemptArray['password'], false);

                // Try to authenticate user
                if($user = $this->auth->Authenticate($loginAttemptUser)) {

                    $this->DoLoginSuccess($user);

                } else {

                    // The user was denied access
                    throw new \Exception("Wrong name or password");
                }
            }

            // If login is saved on client
            else if($this->formView->IsLoginSavedOnClient() && !$this->auth->IsUserLoggedIn()) {

                // Get client login info
                $userInfoArray = $this->formView->GetLoginSavedOnClient();

                $user = new \model\user(NULL, $userInfoArray['username'], NULL, false, false, $userInfoArray['token'], false);

                if($this->auth->AuthenticatePersistent($user)) {

                    $this->DoLoginSuccess($user);

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
        assert($this->auth->IsUserLoggedIn());

        // Only logout user if logged in
        if($this->auth->IsUserLoggedIn()) {

            // Start session if its not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Set a logout message to be displayed for the user.
            $this->formView->SetLoggedOutMessage();

            // Clear user login
            $this->auth->ForgetUserLoggedIn();

            // Clear persistent client login data, if there is any.
            if($this->formView->IsLoginSavedOnClient()) {
                $this->formView->DeleteLoginSavedOnClient();
            }

            // The user logged out successfully, reload page
            $this->appController->ReloadPage();
        }
    }

    public function GetOutput(){
        return $this->formView->GetHTML();
    }

// Private methods

    private function DoLoginSuccess($user) {

        // Store logged in user object in sessions cookie
        $this->auth->KeepUserLoggedInForSession($user);

        // Check if user wants login to be remembered
        if($this->formView->DoesUserWantLoginToBeRemembered()) {

            // Save persistent login on server
            $this->auth->SaveLoginOnServer($user);

            // Save persistent login on client
            $this->formView->SaveLoginOnClient($user);
        }

        // Set a login message to be displayed for the user.
        $this->formView->SetLoggedInMessage();

        $this->appController->ReloadPage();
    }

} 