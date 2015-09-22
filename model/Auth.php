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


// Public methods

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
    public function GenerateToken($length = 16) {
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

        // Get user from database, if user exists
        $userFromDB = $this->users->GetUserByUsername($user->GetUserName());

        if($userFromDB) {

            // Verify password in user object against password in db table row.
            if(password_verify($user->GetPassword(), $userFromDB->GetPassword())) {

                // Hash password in user object. Does no need to be in clear text anymore.
                $user->HashPassword();

                // Add id from DBuser to user
                $user->SetUserId($userFromDB->GetUserId());

                // Return user from DB
                return $user;
            }
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

    public function KeepUserLoggedInForSession(\model\User $user) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user object in a session cookie.
        $_SESSION[self::$SESSION_COOKIE_NAME] = $user;
    }

    public function SaveLoginOnServer(\model\User $user){
        $this->users->AddPersistentLogin($user);
    }

    public function ForgetUserLoggedIn() {
        unset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }



// Private methods
} 