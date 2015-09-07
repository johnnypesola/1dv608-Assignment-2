<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 01:33
 */

namespace controller;

class ValidationController {

    private static $USERNAME_REGEX = '/[^a-z_\-0-9]/i';
    private static $USERNAME_REGEX_ERROR_MSG = "Invalid username. It should be alpha numeric.";
    private static $USERNAME_EMPTY_ERROR_MSG = "Username is missing";
    private static $USERNAME_MAX_LENGTH = 30;
    private static $USERNAME_MAX_LENGTH_ERROR_MSG = "Username is too long. Max length is 30 chars";

    private static $PASSWORD_REGEX = '/[^a-z_\-0-9]/i';
    private static $PASSWORD_REGEX_ERROR_MSG = "Invalid password. It should be alpha numeric.";
    private static $PASSWORD_EMPTY_ERROR_MSG = "Password is missing";
    private static $PASSWORD_MAX_LENGTH = 30;
    private static $PASSWORD_MAX_LENGTH_ERROR_MSG = "Password is too long. Max length is 30 chars";

    public static function IsValidUsername($username) {

        // Check if username is empty
        if(trim(strlen($username)) == 0) {
            throw new \Exception(self::$USERNAME_EMPTY_ERROR_MSG);
        }

        // Check if username is valid
        if(preg_match(self::$USERNAME_REGEX, $username)) {
            throw new \Exception(self::$USERNAME_REGEX_ERROR_MSG);
        }

        // Check that username is not too long
        if(strlen($username) > self::$USERNAME_MAX_LENGTH) {
            throw new \Exception(self::$USERNAME_MAX_LENGTH_ERROR_MSG);
        }

        return true;
    }

    public static function IsValidPassword($password) {

        // Check if password is empty
        if(trim(strlen($password)) == 0) {
            throw new \Exception(self::$PASSWORD_EMPTY_ERROR_MSG);
        }

        // Check if password is valid
        if(preg_match(self::$PASSWORD_REGEX, $password)) {
            throw new \Exception(self::$PASSWORD_REGEX_ERROR_MSG);
        }

        // Check that username is not too long
        if(strlen($password) > self::$PASSWORD_MAX_LENGTH) {
            throw new \Exception(self::$PASSWORD_MAX_LENGTH_ERROR_MSG);
        }

        return true;
    }
} 