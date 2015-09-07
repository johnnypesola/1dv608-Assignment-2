<?php

namespace model;


class UserModel {

    // Init variables
    private $username;
    private static $USERNAME_ERROR_MSG = "Invalid username. It should be alpha numeric, contain minimum 3 chars and max 20 chars.";

    // Constructor
    public function __construct($username) {

        $this->SetUserName($username);
    }

    // Getters and Setters
    # Username
    public function SetUserName($value) {

        // Check if username is valid
        if(\controller\ValidationController::IsValidPassword($value)) {
            // Set username
            $this->username = trim($value);
        } else {
            throw new \Exception(self::$USERNAME_ERROR_MSG);
        }
    }

    public function GetUsername() {
        return $this->username;
    }
} 