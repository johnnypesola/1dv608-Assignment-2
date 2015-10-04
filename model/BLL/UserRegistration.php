<?php

namespace model;


class UserRegistration extends BLLBase {

// Init variables
    private $userName;
    private $password;

    private static $constraints = [
        'username' => [
            'regexMsg' => "Username contains invalid characters.",
            'emptyMsg' => "Username has too few characters, at least 3 characters.",
            'minLength' => 3,
            'maxLength' => 30,
            'maxMsg' => "Username is too long. Max length is 30 characters."
        ],
        'password' => [
            'regexMsg' => "Password contains invalid characters.",
            'emptyMsg' => "Password has too few characters, at least 6 characters.",
            'minLength' => 6,
            'maxLength' => 30,
            'maxMsg' => "Password is too long. Max length is 30 characters.",
            'doNotMatchMsg' => "Passwords do not match."
        ]
    ];

// Constructor
    public function __construct(
        $username,
        $password = "",
        $passwordRepeat = ""
    ) {
        $this->SetUserName($username);
        $this->SetPassword($password, $passwordRepeat);
    }

// Getters and Setters

    # Username
    public function SetUserName($value) {

        // Check if username is valid
        if($this->IsValidString("Username", $value, self::$constraints["username"])) {

            // Set username
            $this->userName = trim($value);

            return true;
        }

        return false;
    }

    public function GetUserName() {
        return $this->userName;
    }

    # Password
    public function SetPassword($value, $repeatValue) {

        // Check if passwords match
        if($value != $repeatValue) {
            ValidationService::AddValidationError(self::$constraints['password']['doNotMatchMsg']);

            return false;
        }

        // Check if password is valid
        if ($this->IsValidString("Password", $value, self::$constraints["password"])) {

            // Set password
            $this->password = trim($value);

            return true;
        }

        return false;
    }

    public function GetPassword() {
        return $this->password;
    }


// Private Methods

}