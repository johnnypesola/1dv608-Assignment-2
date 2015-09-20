<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 14:08
 */

namespace model;


class Users extends DBBase {

// Init variables
    private static $usernameLastLoginAttempt = "";
    private static $SESSION_COOKIE_NAME = "user_logged_in";

    private static $DB_TABLE_NAME = 'ass2_user';


// Constructor

// Getters and setters
/*
    public function GetLastLoginAttemptUsername() {
        return self::$usernameLastLoginAttempt;
    }

    public function SetLastLoginAttemptUsername($value) {
        self::$usernameLastLoginAttempt = $value;
    }
*/

// Public Methods

    public function IsUserLoggedIn() {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION[self::$SESSION_COOKIE_NAME]);
    }

    public function GetAll() {

        $returnArray = [];

        // Get data from database
        foreach(self::$db->query('SELECT `user_id`, `user_name` FROM `' . self::$DB_TABLE_NAME  . '`') as $row ) {

            // Create new user object from database row
            $usersArray[] = new User($row['user_id'], $row['user_name'], $row['user_password']);
        }

        return $returnArray;
    }

    public function GetAllWithPasswords() {

        $returnArray = [];

        // Get data from database
        foreach(self::$db->query('SELECT * FROM `' . self::$DB_TABLE_NAME  . '`') as $row ) {
            $returnArray[] = $row;
        }

        return $returnArray;
    }

    public function GetUserByUsername($username) {

        // Prepare db statement
        $statement = self::$db->prepare(
            'SELECT * FROM ' . self::$DB_TABLE_NAME  .
            ' WHERE `user_name` = :userName'
        );

        // Prepare input array
        $inputArray = [
            'userName' => $username
        ];

        // Execute db statement
        $statement->execute($inputArray);

        // Fetch rows
        $userRowsArray = $statement->fetchAll();

        // Assert that there is no more than 1 users in database
        assert(sizeof($userRowsArray) <= 1);

        // Return found user or false
        if(sizeof($userRowsArray) > 0) {

            return new \model\User(
                    $userRowsArray[0]['user_id'],
                    $userRowsArray[0]['user_name'],
                    $userRowsArray[0]['user_password'],
                    false,
                    false
            );
        }

        return false;
    }

    public function Add(\model\User $user){

        // Prepare db statement
        $statement = self::$db->prepare(
            'INSERT INTO ' . self::$DB_TABLE_NAME  .
            '(user_id, user_name, user_password)' .
            ' VALUES ' .
            '(NULL, :userName, :password)'
        );

        // Prepare input array
        $inputArray = [
            'userName' => $user->GetUserName(),
            'password' => $user->GetPassword()
        ];

        // Execute db statement
        $statement->execute($inputArray);

        // Check if db insertion was successful
        return $statement->rowCount() == 1;
    }

    public function Authenticate(\model\User $user) {

        // Assert that the password is in plain text.
        assert($user->IsPasswordHashed() == false);

        // Get user from database, if user exists
        $userFromDB = $this->GetUserByUsername($user->GetUserName());

        if($userFromDB) {
            // Verify password in user object against password in db table row.
            return password_verify($user->GetPassword(), $userFromDB->GetPassword());
        }

        return false;
    }

    public function StoreLoginInSessionCookie(User $userObj) {

        // Start session if its not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user object in a session cookie.
        $_SESSION[self::$SESSION_COOKIE_NAME] = $userObj;
    }





} 