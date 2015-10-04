<?php

namespace model;


class User {

// Init variables
    private $userId;
    private $userName;
    private $password;
    private $token;
    private $signature;

    private $passwordHashed = false;
    private $tokenHashed = false;

    private static $USER_ID_ERROR_MSG = "Invalid user id. It should be numeric and above 0.";

    private static $STRING_FIELD_CONSTRAINTS = [
        'USERNAME' => [
            'REGEX' => '/[^a-z_\-0-9]/i',
            'REGEX_ERROR_MSG' => "Invalid username. It should be alpha numeric.",
            'EMPTY_ERROR_MSG' => "Username is missing",
            'MAX_LENGTH' => 30,
            'MAX_LENGTH_ERROR_MSG' => "Username is too long. Max length is 30 chars"
        ],
        'PASSWORD' => [
            'REGEX' => '/[^a-z_\-0-9]/i',
            'REGEX_ERROR_MSG' => "Invalid password. It should be alpha numeric.",
            'EMPTY_ERROR_MSG' => "Password is missing",
            'MAX_LENGTH' => 30,
            'MAX_LENGTH_ERROR_MSG' => "Password is too long. Max length is 30 chars"
        ],
        'TOKEN' => [
            'REGEX' => '/[^a-z_\-0-9]/i',
            'REGEX_ERROR_MSG' => "Invalid token. It should be alpha numeric.",
            'MAX_LENGTH' => 255,
            'MAX_LENGTH_ERROR_MSG' => "Token is too long. Max length is 255 chars"
        ]
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
            throw new \Exception(self::$USER_ID_ERROR_MSG);
        }
    }

    public function GetUserId() {
        return $this->userId;
    }

    # Username
    public function SetUserName($value) {

        // Check if username is valid
        if($this->IsValidString('USERNAME', $value)) {

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
        if(!$doCheckPassword || $this->IsValidString('PASSWORD', $value)) {

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
        if($this->IsValidString('TOKEN', $value)) {

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

    private function IsValidString($type, $value) {

        // Check if value is empty
        if(isset(self::$STRING_FIELD_CONSTRAINTS[$type]['EMPTY_ERROR_MSG']) && trim(strlen($value)) == 0) {
            throw new \Exception(self::$STRING_FIELD_CONSTRAINTS[$type]['EMPTY_ERROR_MSG']);
        }

        // Check if value is valid
        if(preg_match(self::$STRING_FIELD_CONSTRAINTS[$type]['REGEX'], $value)) {
            throw new \Exception(self::$STRING_FIELD_CONSTRAINTS[$type]['REGEX_ERROR_MSG']);
        }

        // Check that value is not too long
        if(strlen($value) > self::$STRING_FIELD_CONSTRAINTS[$type]['MAX_LENGTH']) {
            throw new \Exception(self::$STRING_FIELD_CONSTRAINTS[$type]['MAX_LENGTH_ERROR_MSG']);
        }

        return true;
    }

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