<?php

namespace view;

class FormView extends BaseView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $username = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

    private $usersObj;

    // Constructor
    public function __construct(\model\Users $usersObj) {
        $this->usersObj = $usersObj;
    }

    // Methods
	public function Render() {
        // Init vars
        $message = '';

        // Get error messages if there are any.
        /*
        if($this->AppController->errorModel->HasErrors()) {
            $message = $this->AppController->errorModel->GetLastErrorMessage();
        }
        */

        if($this->usersObj->IsUserLoggedIn())
        {
            $this->RenderLogoutForm($message);
        } else {
            $this->RenderLoginForm($message);
        }
	}

	private function RenderLogoutForm($message) {
		echo '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	private function RenderLoginForm($message) {
		echo '<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					<label for="' . self::$username . '">Username :</label>
					<input type="text" id="' . self::$username . '" name="' . self::$username . '" value="' . $this->usersObj->GetLastLoginAttemptUsername() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

    private function GetLoggedIn() {
        return ($this->usersObj->IsUserLoggedIn() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }

    private function GetTime() {

        return date('l, \t\h\e jS \o\f F Y, \T\h\e \t\i\m\e \i\s H:i:s');
    }
}