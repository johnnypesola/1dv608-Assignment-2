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

        // Process Login
        $this->ProcessLogin();
    }

// Public methods
    public function ProcessLogin() {


        $username = "Mario";

        $token = $this->auth->GenerateToken();

        $signature = $this->auth->Hash($username . $token);

        echo "here -> " . $signature . $this->auth->AuthenticatePersistent($username, $token, $signature);

        //echo $this->auth->verify("fisk", $hashed);





        // Try to authenticate
        try {

            // If user is logged in and wants to logout
            if($this->formView->UserWantsToLogout() && $this->auth->IsUserLoggedIn()) {
                $this->Logout();
            }

            // If user wants to login
            else if($this->formView->UserWantsToLogin()) {

                // Get login attempt
                $loginAttemptArray = $this->formView->GetLoginAttempt();

                // Create new user from login attempt
                $loginAttemptUser = new \model\User(NULL, $loginAttemptArray['username'], $loginAttemptArray['password'], false);

                // Try to authenticate user
                if($this->auth->Authenticate($loginAttemptUser)) {

                    // Store logged in user object in sessions cookie
                    $this->auth->KeepUserLoggedIn($loginAttemptUser);

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

            // The user logged out successfully, reload page
            $this->appController->ReloadPage();
        }
    }

    public function GetOutput(){
        return $this->formView->GetHTML();
    }

// Private methods


} 