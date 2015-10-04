<?php

namespace controller;

class AppController extends AppBaseController {

    private $output, $navigationView, $mainController, $auth;

    public function __construct() {

        // Initiate output var
        $this->output = '';

        // Setup application
        $this->Setup('Login Example', 'Assignment 2');

        // Create auth service model
        $this->auth = new \model\AuthService();

        // Create Navigation view object
        $this->navigationView = new \view\NavigationView($this, $this->auth);

        // Create main controller objects, depending on what user wants to see.
        if($this->navigationView->UserWantsToRegister())
        {
            $this->mainController = new\controller\RegistrationController($this, $this->auth);
        } else {
            $this->mainController = new \controller\LoginController($this, $this->auth);
        }

        // Get output from navigationView
        $this->output .= $this->navigationView->GetOutput();

        // Get output from mainController
        $this->output .= $this->mainController->GetOutput();

        // Render main controllers return view as content in HTMLview
        $this->HTMLView->Render($this->output);
    }
}
