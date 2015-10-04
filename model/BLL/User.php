<?php

namespace model;


class User extends BLLBase {

// Init variables
    private $userId;
    private $userName;
    private $password;
    private $token;
    private $signature;

    private $passwordHashed = false;
    private $tokenHashed = false;

    private static $userIdErrorMsg = "Invalid user id. It should be numeric and above 0.";

    private static $constraints = [
        'username' => ['maxLength' => 30, 'minLength' => 1, 'throwException' => true],
        'password' => ['maxLength' => 30],
        'token' => ['maxLength' => 255]
    ];

// Constructor
    public function __construct(
        $userId = null,
        $username,
        $password = "",
        $doHashPassword = true,
        $doCheckPassword = true,
        $token = "",
        $doHashToken = true
    ) {
        $this->SetUserId($userId);
        $this->SetUserName($username);
        $this->SetPassword($password, $doHashPassword, $doCheckPassword);
        $this->SetToken($token, $doHashToken);
        $this->SetSignature();
    }

// Getters and Setters

    # UserId
    public function SetUserId($value) {

        // Check if user id is valid
        if(is_null($value) || is_numeric($value) && $value > 0) {

            // Set user id
            $this->userId = (int) $value;
        } else {
            throw new \Exception(self::$userIdErrorMsg);
        }
    }

    public function GetUserId() {
        return $this->userId;
    }

    # Username
    public function SetUserName($value) {

        // Check if username is valid
        if($this->IsValidString("Username", $value, self::$constraints["username"])) {

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
        if(!$doCheckPassword || $this->IsValidString("Password", $value, self::$constraints["password"])) {

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

    # Token
    public function SetToken($value, $doHashToken = true) {

        // If token is empty
        if(strlen($value) <=1) {
            $value = \model\AuthService::GenerateToken();
        }

        // Check if token is valid
        if($this->IsValidString("Token", $value, self::$constraints["token"])) {

            // Hash token
            if($doHashToken) {
                $this->token = \model\AuthService::Hash($value);
                $this->tokenHashed = true;
            } else {
                $this->token = trim($value);
            }
        }
    }

    public function GetToken() {
        return $this->token;
    }

    # Signature
    public function SetSignature() {

        // Set signature from combining username and token
        $this->signature = \model\AuthService::Hash($this->GetUserName() . $this->GetToken());

    }

    public function GetSignature() {
        return $this->signature;
    }


// Private Methods

// Public Methods
    public function IsPasswordHashed() {
        return $this->passwordHashed;
    }

    public function HashPassword() {

        // Assert that password is not hashed already
        assert(!$this->IsPasswordHashed());

        // Hash password through set method
        $this->SetPassword($this->password);
    }

    public function IsTokenHashed() {
        return $this->tokenHashed;
    }
} 