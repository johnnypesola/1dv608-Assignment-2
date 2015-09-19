<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-17
 * Time: 14:51
 */

namespace controller;


abstract class AppBaseController {

    public $HTMLView;
    protected $errorModel;
    protected $mainController;

    protected function Setup($pageTitle, $pageHeader) {

        // Create error model object
        $this->errorModel = new \model\ErrorArray();

        // Create HTML view object
        $this->HTMLView = new \view\HTMLView($pageTitle, $pageHeader);

    }
}