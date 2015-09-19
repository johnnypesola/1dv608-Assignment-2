<?php

namespace controller;

class AppController extends AppBaseController {

    public function __construct() {

        // Setup application
        $this->Setup('Login Example', 'Assignment 2');

        // Create main controller object
        $this->mainController = new \controller\LoginController($this);

        // Render main controllers return view as content in HTMLview
        $this->HTMLView->Render($this->mainController->GetOutput());
    }
}
