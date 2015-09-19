<?php

namespace model;


class User {

// Init variables
    private $userId;
    private $userName;
    private $password;

    private $passwordHashed = false;

    private static $USERID_ERROR_MSG = "Invalid user id. It should be numeric and above 0.";

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

// Constructor
    public function __construct($id = null, $username, $password = "", $doHashPassword = true, $doCheckPassword = true) {
        $this->SetUserId($id);
        $this->SetUserName($username);
        $this->SetPassword($password, $doHashPassword, $doCheckPassword);
    }

// Getters and Setters

    # UserId
    public function SetUserId($value) {

        // Check if user id is valid
        if($this->IsValidUserId($value)) {

            // Set user id
            $this->userId = (int) $value;
        }
    }

    public function GetUserId() {
        return $this->userName;
    }

    # Username
    public function SetUserName($value) {

        // Check if username is valid
        if($this->IsValidUsername($value)) {

            // Set username
            $this->userName = trim($value);
        }
    }

    public function GetUserName() {
        return $this->userName;
    }

    # Password
    public function SetPassword($value, $doHashPassword = true, $doCheckPassword = true) {

        // Check if password is valid
        if(!$doCheckPassword || $this->IsValidPassword($value)) {

            // Set password
            if($doHashPassword) {
                $this->password = password_hash(trim($value), PASSWORD_DEFAULT);
                $this->passwordHashed = true;
            } else {
                $this->password = trim($value);
            }
        }
    }

    public function GetPassword() {
        return $this->password;
    }

// Private methods

    private function IsValidUserId($id) {

        // Check if username is empty
        if(!is_null($id) && trim(strlen($id)) == 0 || !is_null($id) && !is_numeric($id)) {
            throw new \Exception(self::$USERID_ERROR_MSG);
        }

        return true;
    }

    private function IsValidUsername($username) {

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

    private function IsValidPassword($password) {

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

// Public methods
    public function IsPasswordHashed() {
        return $this->passwordHashed;
    }
} 