<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 01:35
 */

namespace model;


class UserModel {

    // Init variables
    private $username;
    private static $USERNAME_ERROR_MSG = "Invalid username. It should be alpha numeric, contain minimum 3 chars and max 20 chars.";

    // Constructor
    public function __construct($username) {

        $this->setUserName($username);
    }

    // Getters and Setters
    # Username
    public function setUserName($value) {

        // Check if username is valid
        if(\controller\ValidationController::isValidPassword($value)) {
            // Set username
            $this->username = trim($value);
        } else {
            throw new \Exception(self::$USERNAME_ERROR_MSG);
        }
    }

    public function getUsername() {
        return $this->username;
    }

} 