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

        // Create form view object
        $this->registrationView = new \view\RegistrationView($this->appController->exceptions);

    }

// Public methods
    public function GetOutput() {

        return $this->registrationView->GetHTML();
    }
} 