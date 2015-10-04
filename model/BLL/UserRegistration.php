<?php

namespace model;


class UserRegistration {

// Init variables
    private $userName;
    private $password;

    private static $STRING_FIELD_CONSTRAINTS = [
        'USERNAME' => [
            'REGEX' => '/[^a-z_\-0-9]/i',
            'REGEX_ERROR_MSG' => "Username contains invalid characters.",
            'EMPTY_ERROR_MSG' => "Username has too few characters, at least 3 characters.",
            'MIN_LENGTH' => 3,
            'MAX_LENGTH' => 30,
            'MAX_LENGTH_ERROR_MSG' => "Username is too long. Max length is 30 chars"
        ],
        'PASSWORD' => [
            'REGEX' => '/[^a-z_\-0-9]/i',
            'REGEX_ERROR_MSG' => "Password contains invalid characters.",
            'EMPTY_ERROR_MSG' => "Password has too few characters, at least 6 characters.",
            'MIN_LENGTH' => 6,
            'MAX_LENGTH' => 30,
            'MAX_LENGTH_ERROR_MSG' => "Password is too long. Max length is 30 chars",
            'DO_NOT_MATCH' => "Passwords do not match."
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
        if($this->IsValidString('USERNAME', $value)) {

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
            ValidationService::AddValidationError(self::$STRING_FIELD_CONSTRAINTS['PASSWORD']['DO_NOT_MATCH']);

            return false;
        }

        // Check if password is valid
        if($this->IsValidString('PASSWORD', $value)) {

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

    private function IsValidString($type, $value) {

        // Check if value is too short
        if(
            isset(self::$STRING_FIELD_CONSTRAINTS[$type]['EMPTY_ERROR_MSG']) &&
            trim(strlen($value)) < self::$STRING_FIELD_CONSTRAINTS[$type]['MIN_LENGTH']
        ) {
            ValidationService::AddValidationError(
                self::$STRING_FIELD_CONSTRAINTS[$type]['EMPTY_ERROR_MSG']
            );
        }

        // Check if value is valid
        if(preg_match(self::$STRING_FIELD_CONSTRAINTS[$type]['REGEX'], $value)) {
            ValidationService::AddValidationError(
                self::$STRING_FIELD_CONSTRAINTS[$type]['REGEX_ERROR_MSG']
            );
        }

        // Check that value is not too long
        if(strlen($value) > self::$STRING_FIELD_CONSTRAINTS[$type]['MAX_LENGTH']) {
            ValidationService::AddValidationError(
                self::$STRING_FIELD_CONSTRAINTS[$type]['MAX_LENGTH_ERROR_MSG']
            );
        }

        return true;
    }
} 