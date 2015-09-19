<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-17
 * Time: 15:28
 */

namespace view;


class BaseView {

    private $AppController;

    // Constructor
    public function __construct($AppController) {

        // Get application controller
        $this->AppController = $AppController;
    }

    public function Render() {

    }
} 