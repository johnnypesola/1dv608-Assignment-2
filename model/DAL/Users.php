<?php


namespace model;


class Users extends DBBase {

// Init variables
    private static $DB_TABLE_NAME = 'ass2_user';

    private static $DB_QUERY_ERROR = 'Error getting users from database';
    private static $DB_GET_ERROR = 'Error getting user from database';
    private static $DB_INSERT_ERROR = 'Error adding user to database';
    private static $DB_UPDATE_ERROR = 'Error updating user in database';


// Constructor

// Public Methods

    public function GetAll() {

        try {

            $returnArray = [];

            // Get data from database
            foreach(self::$db->query('SELECT `user_id`, `user_name` FROM `' . self::$DB_TABLE_NAME  . '`') as $row ) {

                // Create new user object from database row
                $usersArray[] = new User($row['user_id'], $row['user_name'], $row['user_password']);
            }

            return $returnArray;

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_QUERY_ERROR);
        }

    }

    public function GetAllWithPasswords() {

        try {

            $returnArray = [];

            // Get data from database
            foreach(self::$db->query('SELECT * FROM `' . self::$DB_TABLE_NAME  . '`') as $row ) {
                $returnArray[] = $row;
            }

            return $returnArray;

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_QUERY_ERROR);
        }

    }

    public function GetUserByUsername($username) {

        try {

            // Prepare db statement
            $statement = self::$db->prepare(
                'SELECT * FROM ' . self::$DB_TABLE_NAME .
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
            if (sizeof($userRowsArray) > 0) {

                // Create new user
                return new \model\User(
                    $userRowsArray[0]['user_id'],
                    $userRowsArray[0]['user_name'],
                    $userRowsArray[0]['user_password'],
                    false,
                    false,
                    $userRowsArray[0]['user_token_hash'],
                    false
                );
            }

            return false;

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_GET_ERROR);
        }

    }

    public function Add(\model\User $user){

        try {
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

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_INSERT_ERROR);
        }

    }

    public function AddPersistentLogin(\model\User $user) {

        try {

            // Assert that token is hashed
            assert($user->IsTokenHashed());

            // Assert that user has id
            assert(is_numeric($user->GetUserId()));

            // Prepare db statement
            $statement = self::$db->prepare(
                'UPDATE ' . self::$DB_TABLE_NAME .
                ' SET `user_token_hash` = :token' .
                ' WHERE `user_id` = :userId'
            );

            // Prepare input array
            $inputArray = [
                'userId' => $user->GetUserId(),
                'token' => $user->GetToken()
            ];

            // Execute db statement
            $statement->execute($inputArray);

            // Check if db insertion was successful
            return $statement->rowCount() == 1;

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_UPDATE_ERROR);
        }
    }

} 