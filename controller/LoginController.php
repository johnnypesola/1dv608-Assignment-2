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
    private $Application;
    private $userObj;

    private static $usernameInputFieldName = "LoginView::UserName";
    private static $passwordInputFieldName = "LoginView::Password";
    private static $SubmitFieldName = "LoginView::Login";

// Constructor
    public function __construct(\App $Application) {

        // Get parent application object
        $this->Application = $Application;
    }

// Public methods
    public function processLogin() {

        // Try to authenticate
        try {

            // Get login attempt, if it exist.
            $loginAttemptObj = $this->getLoginAttempt();

            // If there is a login attempt
            if($loginAttemptObj !== null) {


                    if(self::authenticate($loginAttemptObj->getUsername(), $loginAttemptObj->getPassword())) {

                        // Create user object
                        $this->userObj = new UserModel($loginAttemptObj->getUsername());

                        // Store logged in user object in sessions cookie
                        \model\UsersModelDAL::storeLoginInSessionCookie($this->userObj);

                        // Return login success
                        return true;
                    } else {
                        throw new \Exception("Wrong name or password");
                    }
                }
        } catch (\Exception $exception) {

            // Store error in application errors
            $this->Application->errorModel->addError($exception);
        }

        // Return login failure
        return false;
    }

    public static function logout() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy session cookie
        session_destroy();
    }

// Private methods
    private function getLoginAttempt() {

        // If username and password are posted
        if(isset($_POST[self::$SubmitFieldName]))
        {
            $username = isset($_POST[self::$usernameInputFieldName]) ? $_POST[self::$usernameInputFieldName] : "";
            $password = isset($_POST[self::$passwordInputFieldName]) ? $_POST[self::$passwordInputFieldName] : "";

            // Create and return a LoginAttempt model object.
            return new \model\LoginAttemptModel($username, $password);

        }

        return null;
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


    private static function authenticate($username, $password) {

        // Get valid users with passwords
        $validUsersArray = \model\UsersModelDAL::getUsersWithPasswords();

        // Try to authenticate users to static user array.
        foreach($validUsersArray as $validUsername => $validPassword) {
            if($username == $validUsername && $password == $validPassword) {
                return true;
            }
        }
        return false;
    }


} 