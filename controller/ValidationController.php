<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 01:33
 */

namespace controller;

class ValidationController {

    private static $USERNAME_REGEX = "/^w{3,20}$/";
    private static $USERNAME_REGEX_ERROR_MSG = "Invalid username. It should be alpha numeric, contain minimum 3 chars and max 20 chars.";
    private static $USERNAME_EMPTY_ERROR_MSG = "Username is missing";


    private static $PASSWORD_REGEX = "/^w{3,20}$/";
    private static $PASSWORD_REGEX_ERROR_MSG = "Invalid password. It should be alpha numeric, contain minimum 3 chars and max 20 chars.";
    private static $PASSWORD_EMPTY_ERROR_MSG = "Password is missing";

    public static function isValidUsername($username) {

        // Check if username is empty
        if(trim(strlen($username)) == 0) {
            throw new \Exception(self::$USERNAME_EMPTY_ERROR_MSG);
        }

        // Check if username is valid
        if(!preg_match(self::$USERNAME_REGEX, $username)) {
            throw new \Exception(self::$USERNAME_REGEX_ERROR_MSG);
        }

        return true;
    }

    public static function isValidPassword($password) {

        // Check if password is empty
        if(trim(strlen($password)) == 0) {
            throw new \Exception(self::$PASSWORD_EMPTY_ERROR_MSG);
        }

        // Check if password is valid
        if(!preg_match(self::$PASSWORD_REGEX, $password)) {
            throw new \Exception(self::$PASSWORD_REGEX_ERROR_MSG);
        }

        return true;
    }
} 