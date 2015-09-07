<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 13:24
 */

namespace model;


class LoginAttemptModel {

    private $username;
    private $password;
    private $timestamp;

    // Constructor
    public function __construct($username, $password) {
        $this->setUserName($username);
        $this->setPassword($password);
        $this->timestamp = time();
    }

    // Getters and Setters
    # Username
    private function setUserName($value) {

        if(\controller\ValidationController::isValidUsername($value)) {
            $this->username = trim($value);
        }
    }

    public function getUsername() {
        return $this->username;
    }

    # Password
    public function setPassword($value) {

        if(\controller\ValidationController::isValidPassword($value)) {
            $this->password = trim($value);
        }
    }

    public function getPassword() {
        return $this->password;
    }

    # Timestamp
    public function getTimestamp() {
        return $this->timestamp;
    }
}