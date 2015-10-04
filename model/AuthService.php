<?php

namespace model;


class AuthService {

// Init variables
    private $users;

    private static $AUTH_KEY_STRING = "c4ded5a7a71e588270f55a49d47db3d444728fe118162c00730846d3f6e2825f";
    private static $SESSION_LOGGED_IN_USER_COOKIE_NAME = "user_logged_in";
    private static $SESSION_LOGGED_IN_USER_CLIENT = "user_logged_in_client";
    private static $SESSION_LAST_LOGIN_UNAME = "last_login_uname";
    private static $HASH_ALGORITHM = "sha512";

// Constructor
    public function __construct() {

        // Create users model
        $this->users = new \model\UsersDAL();
    }

// Getters and Setters

// Private Methods

// Public Methods

    public static function Hash($value) {
        return hash_hmac(self::$HASH_ALGORITHM, $value, self::$AUTH_KEY_STRING);
    }

    // Needed because hash_equals is not a part of php 5.5.9, Prevents timing attacks
    public function DoHashesEqual($str1, $str2) {
        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
            return !$ret;
        }
    }

    // Try to generate as random token as possible
    public static function GenerateToken($length = 16) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public function AuthenticatePersistent(\model\User $user) {

        // Check signature
        if(!$this->DoHashesEqual(self::Hash($user->GetUserName() . $user->GetToken()), $user->GetSignature())) {

            // Signatures does not match
            throw new \UnexpectedValueException("Signature from 'username' and 'token' does not match original 'signature'");
        }

        // Try to get specific user
        $userFromDB = $this->users->GetUserByUsername($user->GetUserName());

        if($userFromDB) {

            // Verify token in user object against token in db table row.
            return $this->DoHashesEqual($user->GetToken(), $userFromDB->GetToken());
        }

        return false;
    }

    public function Authenticate(\model\User $user) {

        // Assert that the password is in plain text.
        assert($user->IsPasswordHashed() == false);

        // Log this login attempt in DAL
        $this->users->AddLoginAttempt($user);

        // Get user from database, if user exists
        $userFromDB = $this->users->GetUserByUsername($user->GetUserName());

        if($userFromDB) {

            // Verify password in user object against password in db table row.
            if(password_verify($user->GetPassword(), $userFromDB->GetPassword())) {

                // Hash password in user object. Does no need to be in clear text anymore.
                $user->HashPassword();

                // Add id from DBuser to user
                $user->SetUserId($userFromDB->GetUserId());

                // Regenerate session
                session_regenerate_id(true);

                // Return user from DB
                return $user;
            }
        }

        return false;
    }

    public function IsUserLoggedIn() {

        return isset($_SESSION[self::$SESSION_LOGGED_IN_USER_COOKIE_NAME]);
    }

    public function isSessionHijacked() {

        // Only check if user is logged in
        if($this->IsUserLoggedIn()) {
            // Get current user client data
            $userClient = new \model\UserClientService();

            // Check if users client data has changed.
            return $userClient->GetHash() !== $_SESSION[self::$SESSION_LOGGED_IN_USER_CLIENT]->GetHash();
        }

        return false;
    }

    public function KeepUserLoggedInForSession(\model\User $user) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        // If session data does not exist
        if(!isset($_SESSION[self::$SESSION_LOGGED_IN_USER_COOKIE_NAME])) {

            // Store user object in a session cookie.
            $_SESSION[self::$SESSION_LOGGED_IN_USER_COOKIE_NAME] = $user;

            // Store user client info in session
            $_SESSION[self::$SESSION_LOGGED_IN_USER_CLIENT] = new \model\UserClientService();
        }
    }

    public function SaveLoginOnServer(\model\User $user){
        $this->users->AddPersistentLogin($user);
    }

    public function ForgetUserLoggedIn() {
        unset($_SESSION[self::$SESSION_LOGGED_IN_USER_COOKIE_NAME]);
    }

    public function SetLoginUsername(\model\User $user) {
        $_SESSION['last_login_uname'] = $user->GetUserName();
    }

    public function GetLoginUsername() {

        if(isset($_SESSION[self::$SESSION_LAST_LOGIN_UNAME])) {

            $returnValue = $_SESSION[self::$SESSION_LAST_LOGIN_UNAME];

            unset($_SESSION[self::$SESSION_LAST_LOGIN_UNAME]);

            return $returnValue;
        }
    }

} 