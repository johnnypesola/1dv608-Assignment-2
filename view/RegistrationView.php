<?php

namespace view;


class RegistrationView {

// Init variables
    private static $USERNAME_INPUT_NAME = 'RegisterView::UserName';
    private static $PASSWORD_INPUT_NAME = 'RegisterView::Password';
    private static $PASSWORD_REPEAT_INPUT_NAME = 'RegisterView::PasswordRepeat';

    private static $MESSAGE_ID = 'RegisterView::Message';

// Constructor
    public function __construct() {

    }

// Private Methods
    private function GetRegisterFormOutput($messageToUser) {
        return '
            <div class="container" >

			<h2>Register new user</h2>
			<form action="?register" method="post" enctype="multipart/form-data">
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$MESSAGE_ID . '">' . $messageToUser . '</p>
					<label for="' . self::$USERNAME_INPUT_NAME . '" >Username :</label>
					<input type="text" size="20" name="' . self::$USERNAME_INPUT_NAME . '" id="' . self::$USERNAME_INPUT_NAME . '" value="' . $this->GetLastRegistrationAttemptUsername() . '" />
					<br/>
					<label for="' . self::$PASSWORD_INPUT_NAME . '">Password  :</label>
					<input type="password" size="20" name="' . self::$PASSWORD_INPUT_NAME . '" id="' . self::$PASSWORD_INPUT_NAME . '" value="" />
					<br/>
					<label for="' . self::$PASSWORD_REPEAT_INPUT_NAME . '" >Repeat password  :</label>
					<input type="password" size="20" name="' . self::$PASSWORD_REPEAT_INPUT_NAME . '" id="' . self::$PASSWORD_REPEAT_INPUT_NAME . '" value="" />
					<br/>
					<input id="submit" type="submit" name="DoRegistration"  value="Register" />
					<br/>
				</fieldset>
			</form>
        ';
    }

    public function GetLastRegistrationAttemptUsername() {
        return isset($_POST[self::$USERNAME_INPUT_NAME]) ? strip_tags($_POST[self::$USERNAME_INPUT_NAME]) : '';
    }

// Public Methods

    public function UserWantsToRegister() {
        return isset($_POST[self::$USERNAME_INPUT_NAME]) && isset($_POST[self::$PASSWORD_INPUT_NAME]);
    }

    public function GetRegistrationAttempt() {

        // Assert that user actually wants to register.
        assert($this->UserWantsToRegister());

        // Return array with register info.
        return array(
            'username' => $_POST[self::$USERNAME_INPUT_NAME],
            'password' => $_POST[self::$PASSWORD_INPUT_NAME],
            'passwordRepeat' => $_POST[self::$PASSWORD_REPEAT_INPUT_NAME]
        );
    }

    public function GetRegistrationCompleteUrlParams() {
        return '?uname=' . $_POST[self::$USERNAME_INPUT_NAME];
    }


    public function GetHTML() {

        $messageToUser = '';

        // Get exception messages if there are any.
        if(\model\ExceptionsService::HasExceptions()) {

            foreach(\model\ExceptionsService::GetAllExceptionMessages() as $message) {
                $messageToUser .= $message . "<br>";
            }
        }
        // Get validations errors if there are any
        else if(!\model\ValidationService::IsValid()) {

            foreach(\model\ValidationService::GetValidationErrors() as $message) {
                $messageToUser .= $message . "<br>";
            }
        }

        // Get flash messages if there are any
        else if(\model\FlashMessageService::DoesExist()){
            $messageToUser .= \model\FlashMessageService::Get();
        }

        // Get output
        return $this->GetRegisterFormOutput($messageToUser);
    }



} 