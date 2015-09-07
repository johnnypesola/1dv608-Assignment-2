<?php

// Settings for application

# Show errors from server. Turn off on public servers.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

class App {

    public $errorModel;
    public $formViewObj;
    public $pageViewObj;
    public $loginControllerObj;

    public function __construct() {

        // Create views object
        $this->pageViewObj = new \view\PageView('Login Example', 'Assignment 2');
        $this->formViewObj = new \view\FormView($this);

        // Create controller object
        $this->loginControllerObj = new \controller\LoginController($this);

        // Create error model object
        $this->errorModel = new \model\ErrorArrayModel();

        // Process login, if there is such
        $this->loginControllerObj->ProcessLogin();

        // Render form view as content in page
        $this->pageViewObj->Render($this->formViewObj);
    }
}
