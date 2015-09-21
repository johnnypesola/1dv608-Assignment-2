<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-22
 * Time: 00:03
 */

namespace model;


abstract class Cookies {

// Init variables
    private static $SESSION_COOKIE_NAME = "user_logged_in";


// Public Methods
    static public function IsUserLoggedIn() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

    static public function KeepUserLoggedIn(User $userObj) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user object in a session cookie.
        $_SESSION[self::$SESSION_COOKIE_NAME] = $userObj;
    }

    static public function ForgetUserLoggedIn() {
        unset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

    
} 