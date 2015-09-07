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
    public function ProcessLogin() {

        // Try to authenticate
        try {

            // Get login attempt, if it exist.
            $loginAttemptObj = $this->GetPOSTLoginAttempt();

            // If there is a login attempt
            if($loginAttemptObj !== null) {

                // Try to authenticate with given credentials
                if(self::Authenticate($loginAttemptObj->GetUsername(), $loginAttemptObj->GetPassword())) {

                    // Create user object
                    $this->userObj = new UserModel($loginAttemptObj->GetUsername());

                    // Store logged in user object in sessions cookie
                    \model\UsersModelDAL::StoreLoginInSessionCookie($this->userObj);

                    // Return login success
                    return true;
                } else {
                    throw new \Exception("Wrong name or password");
                }
            }
        } catch (\Exception $exception) {

            // Store error in application errors
            $this->Application->errorModel->AddError($exception);
        }

        // Return login failure
        return false;
    }

    public static function Logout() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy session cookie
        session_destroy();
    }

// Private methods
    private function GetPOSTLoginAttempt() {

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

    private static function Authenticate($username, $password) {

        // Get valid users with passwords
        $validUsersArray = \model\UsersModelDAL::GetUsersWithPasswords();

        // Try to authenticate users to static user array.
        foreach($validUsersArray as $validUsername => $validPassword) {
            if($username == $validUsername && $password == $validPassword) {
                return true;
            }
        }
        return false;
    }


} 