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
    public function __construct(\model\Users $users, \model\Exceptions $exceptions) {
        $this->users = $users;
        $this->exceptions = $exceptions;
    }

// Private methods

	private function GetLogoutFormOutput($message) {
		return '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logoutInputName . '" value="logout"/>
			</form>
		';
	}
	
	private function GetLoginFormOutput($message) {
		return '<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					<label for="' . self::$usernameInputName . '">Username :</label>
					<input type="text" id="' . self::$usernameInputName . '" name="' . self::$usernameInputName . '" value="' . $this->GetLastLoginAttemptUsername() . '" />

					<label for="' . self::$passwordInputName . '">Password :</label>
					<input type="password" id="' . self::$passwordInputName . '" name="' . self::$passwordInputName . '" />

					<label for="' . self::$keepInputName . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keepInputName . '" name="' . self::$keepInputName . '" />
					
					<input type="submit" name="' . self::$loginInputName . '" value="login" />
				</fieldset>
			</form>
		';
	}

    private function GetLastLoginAttemptUsername() {
        return isset($_POST[self::$usernameInputName]) ? $_POST[self::$usernameInputName] : '';
    }

    private function GetLoggedInOutput() {
        return (\model\Cookies::IsUserLoggedIn() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }

    private function GetTimeOutput() {

        return '<p>' . date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s') . '</p>';
    }


// Public methods

    public function SetLoggedInMessage() {

        \model\FlashMessage::Set('Welcome');
    }

    public function SetLoggedOutMessage() {

        \model\FlashMessage::Set('Bye bye!');
    }

    public function UserWantsToLogin() {
        return isset($_POST[self::$usernameInputName]) && isset($_POST[self::$usernameInputName]);
    }

    public function UserWantsToLogout(){
        return (isset($_POST[self::$logoutInputName]));
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
        $output .= \model\Cookies::IsUserLoggedIn() ? $this->GetLogoutFormOutput($formMessage) : $this->GetLoginFormOutput($formMessage);

        // Get time output
        $output .= $this->GetTimeOutput();

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