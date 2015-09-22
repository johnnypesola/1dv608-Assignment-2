<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-22
 * Time: 01:29
 */

namespace model;


class Auth {

// Init vars
    private $users;

    private static $AUTH_KEY_STRING = "c4ded5a7a71e588270f55a49d47db3d444728fe118162c00730846d3f6e2825f";
    private static $SESSION_COOKIE_NAME = "user_logged_in";
    private static $HASH_ALGORITHM = "sha512";

// Constructor
    public function __construct(\model\Users $users) {
        $this->users = $users;
    }

#############

// Cookie code

    public function auth() {

        // Check if remeber me cookie is present
        if (! isset($_COOKIE["auto"]) || empty($_COOKIE["auto"])) {
            return false;
        }

        // Decode cookie value
        if (! $cookie = @json_decode($_COOKIE["auto"], true)) {
            return false;
        }

        // Check all parameters
        if (! (isset($cookie['user']) || isset($cookie['token']) || isset($cookie['signature']))) {
            return false;
        }

        $var = $cookie['user'] . $cookie['token'];

        // Check Signature
        if (! $this->verify($var, $cookie['signature'])) {
            throw new Exception("Cokies has been tampared with");
        }

        // Check Database
        $info = $this->db->get($cookie['user']);
        if (! $info) {
            return false; // User must have deleted accout
        }

        // Check User Data
        if (! $info = json_decode($info, true)) {
            throw new Exception("User Data corrupted");
        }

        // Verify Token
        if ($info['token'] !== $cookie['token']) {
            throw new Exception("System Hijacked or User use another browser");
        }

        /**
         * Important
         * To make sure the cookie is always change
         * reset the Token information
         */

        $this->remember($info['user']);
        return $info;
    }

    public function remember($user) {
        $cookie = [
            "user" => $user,
            "token" => $this->getRand(64),
            "signature" => null
        ];
        $cookie['signature'] = $this->Hash($cookie['user'] . $cookie['token']);
        $encoded = json_encode($cookie);

        // Add User to database
        $this->db->set($user, $encoded);

        /**
         * Set Cookies
         * In production enviroment Use
         * setcookie("auto", $encoded, time() + $expiration, "/~root/",
         * "example.com", 1, 1);
         */
        setcookie("auto", $encoded); // Sample
    }

## Done

    public function Hash($value) {
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
    public function GenerateToken() {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

#############


// Public methods

    public function AuthenticatePersistent($username, $token, $signature) {

        // Check signature
        if(!$this->DoHashesEqual($this->Hash($username . $token), $signature)) {

            // Signatures does not match
            throw new \UnexpectedValueException("Signature from 'username' and 'token' does not match original 'signature'");
        }

        // Try to get specific user
        $userFromDB = $this->users->GetUserByUsername($username);

        if($userFromDB) {

            // Verify password in user object against password in db table row.
            return password_verify($user->GetPassword(), $userFromDB->GetPassword());
        }

        return true;
    }

    public function Authenticate(\model\User $user) {

        // Assert that the password is in plain text.
        assert($user->IsPasswordHashed() == false);

        // Get user from database, if user exists
        $userFromDB = $this->users->GetUserByUsername($user->GetUserName());

        if($userFromDB) {
            // Verify password in user object against password in db table row.
            return password_verify($user->GetPassword(), $userFromDB->GetPassword());
        }

        return false;
    }

    public function IsUserLoggedIn() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

    public function KeepUserLoggedIn(\model\User $user) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user object in a session cookie.
        $_SESSION[self::$SESSION_COOKIE_NAME] = $user;
    }

    public function ForgetUserLoggedIn() {
        unset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }



// Private methods
} 