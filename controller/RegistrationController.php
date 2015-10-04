<?php

namespace controller;


class RegistrationController {

// Init variables
    private $appController;
    public $registrationView;

// Constructor
    public function __construct($appController, $auth) {

        // Store Application controller reference
        $this->appController = $appController;

        // Store auth model reference
        $this->auth = $auth;

        // Create users DAL object
        $this->users = new \model\UsersDAL();

        // Create form view object
        $this->registrationView = new \view\RegistrationView();

        // Process Actions
        $this->ProcessActions();

    }

// Private methods
    private function ProcessActions () {

        // Try to register
        try {

            // If user wants to register
            if($this->registrationView->UserWantsToRegister()) {

                // Get Registration attempt
                $registrationAttemptArray = $this->registrationView->GetRegistrationAttempt();

                // Create new userRegistration model from registration attempt
                $userRegistrationAttempt = new \model\UserRegistration(
                    $registrationAttemptArray['username'],
                    $registrationAttemptArray['password'],
                    $registrationAttemptArray['passwordRepeat']
                );

                // If there are no validation errors, proceed.
                if(\model\ValidationService::IsValid()) {

                    // Add user in DAL
                    $this->users->Add(
                        new \model\User(
                            null,
                            $userRegistrationAttempt->GetUserName(),
                            $userRegistrationAttempt->GetPassword()
                        )
                    );

                    // Set new message to display for user.
                    \model\FlashMessageService::Set('Registered new user.');

                    // New user registered successfully. Reload page
                    $this->appController->ReloadPage();
                }
            }

        } catch (\Exception $exception) {

            // Store exceptions in applications exceptions container model
            \model\ExceptionsService::AddException($exception);
        }

        // Return registration failure
        return false;
    }

// Public methods
    public function GetOutput() {

        return $this->registrationView->GetHTML();
    }
} 