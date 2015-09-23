<?php

namespace model;


abstract class FlashMessage {

// Init variables
    private static $SESSION_COOKIE_NAME = 'flash_message';


// Public Methods
    static public function DoesExist(){
        return isset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

    static public function Get(){

        $returnValue = false;

        assert(isset($_SESSION[self::$SESSION_COOKIE_NAME]));

        if(isset($_SESSION[self::$SESSION_COOKIE_NAME])) {

            $returnValue = $_SESSION[self::$SESSION_COOKIE_NAME];

            unset($_SESSION[self::$SESSION_COOKIE_NAME]);
        }

        return $returnValue;
    }

    static public function Set($value) {
        $_SESSION[self::$SESSION_COOKIE_NAME] = $value;
    }
} 