<?php

namespace model;


class LoginAttempt {

    private $username;
    private $password;

    // Constructor
    public function __construct($username, $password) {

        // Set last login attempt
        \model\UsersModelDAL::SetLastLoginAttemptUsername($username);

        // Set username, password and timestamp
        $this->SetUserName($username);
        $this->SetPassword($password);
    }

    // Getters and Setters
    # Username
    private function SetUserName($value) {

        if(\controller\ValidationController::IsValidUsername($value)) {
            $this->username = trim($value);
        }
    }

    public function GetUsername() {
        return $this->username;
    }

    # Password
    public function SetPassword($value) {

        if(\controller\ValidationController::IsValidPassword($value)) {
            $this->password = trim($value);
        }
    }

    public function GetPassword() {
        return $this->password;
    }
}