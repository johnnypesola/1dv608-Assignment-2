<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 19:35
 */

class App {

    public $errorModel;

    public $loginViewObj;
    public $dateTimeViewObj;
    public $layoutViewObj;

    public $loginControllerObj;

    public function __construct() {

        // Create views objects
        $this->layoutViewObj = new \view\LayoutView('Login Example', 'Assignment 2');
        $this->loginViewObj = new \view\LoginView($this);
        $this->dateTimeViewObj = new \view\DateTimeView();

        // Create controller objects
        $this->loginControllerObj = new \controller\LoginController($this);

        // Create model objects
        $this->errorModel = new \model\ErrorArrayModel();
    }


}

// Settings for application

# Show errors from server. Turn off on public servers.
error_reporting(E_ALL);
ini_set('display_errors', 'On');