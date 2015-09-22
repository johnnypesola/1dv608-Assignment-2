<?php

namespace view;

class FormView {
	private static $LOGIN_INPUT_NAME = 'LoginView::Login';
	private static $LOGOUT_INPUT_NAME = 'LoginView::Logout';
	private static $USERNAME_INPUT_NAME = 'LoginView::UserName';
	private static $PASSWORD_INPUT_NAME = 'LoginView::Password';
	private static $COOKIE_INPUT_NAME = 'LoginView::CookieName';
	private static $COOKIE_PASSWORD = 'LoginView::CookiePassword';
	private static $KEEP_LOGGED_IN_INPUT_NAME = 'LoginView::KeepMeLoggedIn';
	private static $MESSAGE_ID = 'LoginView::Message';

    private static $COOKIE_ID = 'keep_login';

    private $users;
    private $auth;
    private $exceptions;

    // Constructor
    public function __construct(\model\Users $users, \model\Auth $auth, \model\Exceptions $exceptions) {
        $this->users = $users;
        $this->auth = $auth;
        $this->exceptions = $exceptions;
    }

// Private methods

	private function GetLogoutFormOutput($message) {
		return '
			<form method="post" >
				<p id="' . self::$MESSAGE_ID . '">' . $message .'</p>
				<input type="submit" name="' . self::$LOGOUT_INPUT_NAME . '" value="logout"/>
			</form>
		';
	}
	
	private function GetLoginFormOutput($message) {
		return '<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$MESSAGE_ID . '">' . $message . '</p>
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
        return isset($_POST[self::$USERNAME_INPUT_NAME]) ? $_POST[self::$USERNAME_INPUT_NAME] : '';
    }

    private function GetLoggedInOutput() {
        return ($this->auth->IsUserLoggedIn() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }

    private function GetTimeOutput() {

        return '<p>' . date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s') . '</p>';
    }


##############

// Cookie code





###############



// Public methods

    public function SetLoggedInMessage() {

        \model\FlashMessage::Set('Welcome');
    }

    public function SetLoggedOutMessage() {

        \model\FlashMessage::Set('Bye bye!');
    }

    public function UserWantsToLogin() {
        return isset($_POST[self::$USERNAME_INPUT_NAME]) && isset($_POST[self::$USERNAME_INPUT_NAME]);
    }

    public function UserWantsToLogout(){
        return (isset($_POST[self::$LOGOUT_INPUT_NAME]));
    }

    public function GetHTML() {

        $formMessage = '';

        // Get logged in header text
        $output = $this->GetLoggedInOutput();

        // Get exception messages if there are any.
        if($this->exceptions->HasExceptions()) {
            $formMessage =  $this->exceptions->GetLastExceptionMessage();
        }
        else if(\model\FlashMessage::DoesExist()){
            $formMessage = \model\FlashMessage::Get();
        }

        // Get login or logout form output
        $output .= $this->auth->IsUserLoggedIn() ? $this->GetLogoutFormOutput($formMessage) : $this->GetLoginFormOutput($formMessage);

        // Get time output
        $output .= $this->GetTimeOutput();

        return $output;
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

    public function DoesUserWantsLoginToBeRemembered(){
        return isset($_POST[self::$KEEP_LOGGED_IN_INPUT_NAME]) ? true : false;
    }

    public function SaveLoginOnClient(\model\User $user) {

        // Prepare values
        $cookieValues = implode(':', array($user->GetUserName(), $user->GetToken(), $user->GetSignature()));

        // Save values in cookie
        return setcookie(self::$COOKIE_ID, $cookieValues, time() + 60 * 60 * 24 * 365);
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

        // Create correct variables from array
        list($username, $token, $signature) = explode(':', $_COOKIE[self::$COOKIE_ID]);

        // Return array with data
        return [
            "username" => $username,
            "token" => $token,
            "signature" => $signature
        ];
    }
}