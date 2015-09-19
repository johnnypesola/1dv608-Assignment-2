<?php

namespace view;

class FormView {
	private static $loginInputName = 'LoginView::Login';
	private static $logoutInputName = 'LoginView::Logout';
	private static $usernameInputName = 'LoginView::UserName';
	private static $passwordInputName = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keepInputName = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';


    private $users;

    // Constructor
    public function __construct(\model\Users $users) {
        $this->users = $users;
    }

// Private methods

	private function GetLogoutForm($message) {
		return '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logoutInputName . '" value="logout"/>
			</form>
		';
	}
	
	private function GetLoginForm($message) {
		return '<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					<label for="' . self::$usernameInputName . '">Username :</label>
					<input type="text" id="' . self::$usernameInputName . '" name="' . self::$usernameInputName . '" value="' . $this->users->GetLastLoginAttemptUsername() . '" />

					<label for="' . self::$passwordInputName . '">Password :</label>
					<input type="password" id="' . self::$passwordInputName . '" name="' . self::$passwordInputName . '" />

					<label for="' . self::$keepInputName . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keepInputName . '" name="' . self::$keepInputName . '" />
					
					<input type="submit" name="' . self::$loginInputName . '" value="login" />
				</fieldset>
			</form>
		';
	}

    private function GetLoggedIn() {
        return ($this->users->IsUserLoggedIn() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }

    private function GetTime() {

        return '<p>' . date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s') . '</p>';
    }

// Public methods

    public function UserWantsToLogin() {
        if (isset($_POST[self::$usernameInputName]) && isset($_POST[self::$usernameInputName]) ) {
            return true;
        }
        return false;
    }

    public function GetOutput() {
        // Init vars
        $output = '';
        $message = '';

        // Get error messages if there are any.
        /*
        if($this->AppController->errorModel->HasErrors()) {
            $message = $this->AppController->errorModel->GetLastErrorMessage();
        }
        */

        // Get logged in header text
        $output .= $this->GetLoggedIn();

        // Get login or logout form output
        $output .= $this->users->IsUserLoggedIn() ? $this->GetLogoutForm($message) : $this->GetLoginForm($message);

        // Get time output
        $output .= $this->GetTime();

        return $output;
    }

    public function GetLoginAttempt() {

        // Assert that user actually wants to login.
        assert($this->UserWantsToLogin());

        // Return array with login info.
        return array(
            'username' => $_POST[self::$usernameInputName],
            'password' => $_POST[self::$passwordInputName]
        );
    }
}