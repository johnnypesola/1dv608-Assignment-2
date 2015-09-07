<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 14:08
 */

namespace model;


class UsersModelDAL {

    // Init variables
    private static $usernameLastLoginAttempt = "";
    private static $SESSION_COOKIE_NAME = "user_logged_in";
    private static $VALID_USERS = [
        "admin" => "secretpassword",
        "anotheradmin" => "anotherpassword"
    ];


    // Getters and setters
    public static function GetLastLoginAttemptUsername() {
        return self::$usernameLastLoginAttempt;
    }

    public static function SetLastLoginAttemptUsername($value) {
        self::$usernameLastLoginAttempt = $value;
    }

    // Methods
    public static function GetUsers() {
        // Init vars
        $usersToReturnArray = array();

        // Fetch usernames only
        foreach(self::$VALID_USERS as $user) {
            $usersToReturnArray[] = key($user);
        }

        // Return array with usernames
        return $usersToReturnArray;
    }

    public static function GetUsersWithPasswords() {
        return self::$VALID_USERS;
    }

    public static function StoreLoginInSessionCookie(UserModel $userObj) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user object in a session cookie.
        $_SESSION[self::$SESSION_COOKIE_NAME] = $userObj;
    }

    public static function IsUserLoggedIn() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

} 