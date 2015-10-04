<?php


namespace model;


class UsersDAL extends DBBase {

// Init variables
    private static $DB_TABLE_NAME = 'ass2_user';
    private static $DB_LOGIN_ATTEMPT_TABLE_NAME = 'ass2_login_attempt';

    private static $DB_QUERY_ERROR = 'Error getting users from database';
    private static $DB_GET_ERROR = 'Error getting user from database';
    private static $DB_INSERT_ERROR = 'Error adding user to database';
    private static $DB_LOGIN_ATTEMPT_INSERT_ERROR = 'Error adding login attempt to database';
    private static $DB_UPDATE_ERROR = 'Error updating user in database';
    private static $DB_USERNAME_EXISTS = 'User exists, pick another username.';

    private static $MAX_REGISTRATIONS_PER_HOUR = 30;

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

        if($this->GetRegistrationsForHour() > self::$MAX_REGISTRATIONS_PER_HOUR) {
            throw new \Exception("Max number of registrations per hour reached. Please try again in 30-60 minutes.");
        }


        // Throw exception if username is taken
        if($this->GetUserByUsername($user->GetUserName())) {

            throw new \Exception(self::$DB_USERNAME_EXISTS);
        }

        try {
            // Prepare db statement
            $statement = self::$db->prepare(
                'INSERT INTO ' . self::$DB_TABLE_NAME  .
                '(user_id, user_name, user_password, date_time)' .
                ' VALUES ' .
                '(NULL, :userName, :password, NOW())'
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

    public function AddLoginAttempt(\model\User $user) {

        try {
            // Prepare db statement
            $statement = self::$db->prepare(
                'INSERT INTO ' . self::$DB_LOGIN_ATTEMPT_TABLE_NAME  .
                '(login_id, user_name, date_time)' .
                ' VALUES ' .
                '(NULL, :userName, NOW())'
            );

            // Prepare input array
            $inputArray = [
                'userName' => $user->GetUserName()
            ];

            // Execute db statement
            $statement->execute($inputArray);

            // Check if db insertion was successful
            return $statement->rowCount() == 1;

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_LOGIN_ATTEMPT_INSERT_ERROR);
        }
    }

    public function GetUserLoginsForHour(\model\User $user) {

        try {

            $returnArray = [];

            // Prepare db statement
            $statement = self::$db->prepare(
                'SELECT * FROM ' . self::$DB_LOGIN_ATTEMPT_TABLE_NAME .
                ' WHERE `user_name` = :userName AND ' .
                ' `date_time` LIKE \'' . date("Y-m-d H") . '%\''
            );

            // Prepare input array
            $inputArray = [
                'userName' => $user->GetUserName()
            ];

            // Execute db statement
            $statement->execute($inputArray);

            // Fetch rows
            $userRowsArray = $statement->fetchAll();

            return sizeOf($userRowsArray);

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_QUERY_ERROR);
        }

    }

    public function GetRegistrationsForHour() {

        try {

            $returnArray = [];

            // Prepare db statement
            $statement = self::$db->prepare(
                'SELECT * FROM ' . self::$DB_TABLE_NAME .
                ' WHERE `date_time` LIKE \'' . date("Y-m-d H") . '%\''
            );

            // Execute db statement
            $statement->execute();

            // Fetch rows
            $userRowsArray = $statement->fetchAll();

            return sizeOf($userRowsArray);

        } catch (\Exception $exception) {
            throw new \Exception(self::$DB_QUERY_ERROR);
        }

    }
} 