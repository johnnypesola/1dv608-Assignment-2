<?php

namespace controller;


abstract class AppBaseController {

    public  $HTMLView, $exceptions;
    protected $mainAppUrl;

    protected function Setup($pageTitle, $pageHeader) {

        // Create HTML view object
        $this->HTMLView = new \view\HTMLView($pageTitle, $pageHeader);

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function RedirectTo($fileName) {

        header('Location: ' . $this->mainAppUrl . '/' . $fileName);
        die();
    }

    public function ReloadPage() {

        header('Location: ' . $this->mainAppUrl . $_SERVER['SCRIPT_NAME']);
        die();
    }
}