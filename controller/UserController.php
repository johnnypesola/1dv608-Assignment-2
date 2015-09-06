<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-06
 * Time: 20:23
 */

namespace controller;


use model\UserModel;

class UserController {

    // Init variables
    private static $validUsers = [
        "admin" => "secretpassword",
        "anotheradmin" => "anotherpassword"
    ];

    // Methods
    public function create($username, $password) {
        // TODO Implement
    }

    public function delete($username) {
        // TODO Implement
    }

    public function login($username, $password) {


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

    public static function logout() {

        // Destroy session cookie
        session_start();
        session_destroy();
    }

    private static function auth($username, $password) {

        // Try to authenticate users to static user array.
        return in_array(array($username, $password), self::$validUsers);
    }
} 