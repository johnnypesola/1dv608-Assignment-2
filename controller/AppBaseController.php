<?php

namespace controller;


abstract class AppBaseController {

    public $HTMLView;
    public $exceptions;
    protected $mainController;
    protected $mainAppUrl;

    protected function Setup($pageTitle, $pageHeader) {

        // Create error model object
        $this->exceptions = new \model\Exceptions();

        // Create HTML view object
        $this->HTMLView = new \view\HTMLView($pageTitle, $pageHeader);
    }

    public function RedirectTo($fileName) {

        header('Location: ' . $this->mainAppUrl . '/' . $fileName);
    }

    public function ReloadPage() {

        header('Location: ' . $this->mainAppUrl . $_SERVER['SCRIPT_NAME']);
        die();
    }
}