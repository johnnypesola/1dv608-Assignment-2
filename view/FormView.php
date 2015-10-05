<?php

namespace view;

class FormView {

// Init variables
	private static $LOGIN_INPUT_NAME = 'LoginView::Login';
	private static $LOGOUT_INPUT_NAME = 'LoginView::Logout';
	private static $USERNAME_INPUT_NAME = 'LoginView::UserName';
	private static $PASSWORD_INPUT_NAME = 'LoginView::Password';

	private static $KEEP_LOGGED_IN_INPUT_NAME = 'LoginView::KeepMeLoggedIn';
	private static $MESSAGE_ID = 'LoginView::Message';

    private static $COOKIE_ID = 'LoginView::CookiePassword';
    private static $COOKIE_VALID_DAYS = 30;

    private $auth;

// Constructor
    public function __construct(\model\AuthService $auth) {
        $this->auth = $auth;
    }

// Private methods
	private function GetLogoutFormOutput($messageToUser) {
		return '
			<form method="post" >
				<p id="' . self::$MESSAGE_ID . '">' . $messageToUser .'</p>
				<input type="submit" name="' . self::$LOGOUT_INPUT_NAME . '" value="logout"/>
			</form>
		';
	}
	
	private function GetLoginFormOutput($messageToUser) {
		return '<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$MESSAGE_ID . '">' . $messageToUser . '</p>
					<label for="' . self::$USERNAME_INPUT_NAME . '">Username :</label>
					<input type="text" id="' . self::$USERNAME_INPUT_NAME . '" name="' . self::$USERNAME_INPUT_NAME . '" value="' . $this->GetLastLoginAttemptUsername() . '" />

					<label for="' . self::$PASSWORD_INPUT_NAME . '">Password :</label>
					<input type="password" id="' . self::$PASSWORD_INPUT_NAME . '" name="' . self::$PASSWORD_INPUT_NAME . '" />

					<label for="' . self::$KEEP_LOGGED_IN_INPUT_NAME . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$KEEP_LOGGED_IN_INPUT_NAME . '" name="' . self::$KEEP_LOGGED_IN_INPUT_NAME . '" />
					
					<input type="submit" name="' . self::$LOGIN_INPUT_NAME . '" value="login" />
				</fieldset>
			</form>
		';
	}

    private function GetLastLoginAttemptUsername() {

        // Return last username what was used in login POST attempt.
        if(isset($_POST[self::$USERNAME_INPUT_NAME])) {
            return $_POST[self::$USERNAME_INPUT_NAME];
        }

        // Return registration username if a new user registration was done.
        return $this->auth->GetLoginUsername();
    }

// Public methods



    public function GetLoggedInMessage() {

        return $this->IsLoginSavedOnClient() ? 'Welcome back with cookie' : 'Welcome';
    }

    public function GetLoggedOutMessage() {
        return 'Bye bye!';
    }

    public function UserWantsToLogin() {
        return isset($_POST[self::$USERNAME_INPUT_NAME]) && isset($_POST[self::$PASSWORD_INPUT_NAME]);
    }

    public function UserWantsToLogout(){
        return (isset($_POST[self::$LOGOUT_INPUT_NAME]));
    }

    public function GetLoginAttempt() {

        // Assert that user actually wants to login.
        assert($this->UserWantsToLogin());

        // Return array with login info.
        return array(
            'username' => $_POST[self::$USERNAME_INPUT_NAME],
            'password' => $_POST[self::$PASSWORD_INPUT_NAME]
        );
    }

    public function DoesUserWantLoginToBeRemembered(){
        return isset($_POST[self::$KEEP_LOGGED_IN_INPUT_NAME]) ? true : false;
    }

    public function SaveLoginOnClient(\model\User $user) {

        // Prepare values
        $cookieValues = implode(':', array($user->GetUserName(), $user->GetToken(), $user->GetSignature()));

        // Save values in cookie (expires in 30 days)
        return setcookie(self::$COOKIE_ID, $cookieValues, time() + 60 * 60 * 24 * self::$COOKIE_VALID_DAYS);
    }

    public function DeleteLoginSavedOnClient() {

        // Assert that cookie exists
        assert($this->IsLoginSavedOnClient());

        // Remove cookie, set expiration date to past.
        setcookie (self::$COOKIE_ID, "", time() - 3600);
    }

    public function IsLoginSavedOnClient() {
        return isset($_COOKIE[self::$COOKIE_ID]);
    }

    public function GetLoginSavedOnClient() {

        // Assert that cookie exists
        assert($this->IsLoginSavedOnClient());

        $cookieArray = explode(':', $_COOKIE[self::$COOKIE_ID]);

        // Check that cookie is ok
        if(sizeof($cookieArray) !== 3) {
            throw new \Exception('Wrong information in cookies');
        }

        // Create correct variables from array
        list($username, $token, $signature) = $cookieArray;

        // Return array with data
        return [
            "username" => $username,
            "token" => $token,
            "signature" => $signature
        ];
    }

    public function GetHTML() {

        $messageToUser = '';

        // Get exception messages if there are any.
        if(\model\ExceptionsService::HasExceptions()) {
            $messageToUser =  \model\ExceptionsService::GetLastExceptionMessage();
        }
        // Get validations errors if there are any
        else if(!\model\ValidationService::IsValid()) {

            foreach(\model\ValidationService::GetValidationErrors() as $message) {
                $messageToUser .= $message . "<br>";
            }
        }
        // Get flash messages if there are any
        else if(\model\FlashMessageService::DoesExist()){
            $messageToUser = \model\FlashMessageService::Get();
        }

        // Get login or logout form output
        return $this->auth->IsUserLoggedIn() ? $this->GetLogoutFormOutput($messageToUser) : $this->GetLoginFormOutput($messageToUser);
    }
}