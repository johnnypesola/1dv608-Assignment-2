<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 20:23
 */

namespace controller;


use model\UserModel;

class LoginController {

    // Init variables
    private $loginViewObj;
    private $userObj;

    // Constructor
    public function __construct(\view\LoginView $loginViewObj) {
        $this->loginViewObj = $loginViewObj;
    }

    // Methods
    public function processLogin() {

        // Get login attempt from view, if it exist.
        $loginAttemptObj = $this->loginViewObj->getLoginAttempt();

        // If there is a login attempt
        if($loginAttemptObj !== null) {

            // Try to authenticate
            if(self::authenticate($loginAttemptObj->getUsername(), $loginAttemptObj->getPassword())) {

                // Create user object
                $this->userObj = new UserModel($loginAttemptObj->getUsername());

                // Store logged in user object in sessions cookie
                \model\UsersModelDAL::storeLoginInSessionCookie($this->userObj);

                // Return login success
                return true;
            }
        }

        // Return login failure
        return false;
    }
/*
    public function login($username, $password) {


        //getLoginAttempt

        try {
            // If input validates correct
            if(ValidationController::isValidUsername($username) && ValidationController::isValidUsername($password)) {
                // Try to create user model.
                new UserModel($username);

                // Try to authenticate
                if(self::auth($username, $password)) {

                    // Start session
                    session_start();

                    // Create new user model, and store it in a session cookie.
                    $_SESSION['user_logged_in'];

                    // Return login success
                    return true;
                } else {
                    // Return login failure
                    return false;
                }
            } else {
                // Input did not validate
                return false;
            }
        }
        // In case of improper input it will throw an exception
        catch (\Exception $exception) {

            echo 'ERROR: ' . $exception->getMessage();

            return false;
        }
    }
*/
    public static function logout() {

        // Destroy session cookie
        session_start();
        session_destroy();
    }

    private static function authenticate($username, $password) {

        // Get valid users with passwords
        $validUsersArray = \model\UsersModelDAL::getUsersWithPasswords();

        // Try to authenticate users to static user array.
        return in_array(array($username, $password), $validUsersArray);
    }


} 