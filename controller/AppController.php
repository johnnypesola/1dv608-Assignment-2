<?php

namespace controller;

class AppController extends AppBaseController {

    public function __construct() {

        // Setup application
        $this->Setup('Login Example', 'Assignment 2');

        // Create main controller object
        $this->mainController = new \controller\LoginController($this);

        // Render form view as content in page
        $this->HTMLView->Render();
    }
}
