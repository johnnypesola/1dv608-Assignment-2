<?php

namespace view;

class FormView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $username = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

    private $Application;

    // Constructor
    public function __construct(\App $Application) {

        // Get parent application object
        $this->Application = $Application;
    }

    // Methods
	public function Render() {
        // Init vars
        $message = '';

        // Get error messages if there are any.
        if($this->Application->errorModel->HasErrors()) {
            $message = $this->Application->errorModel->GetLastErrorMessage();
        }

        if(\model\UsersModelDAL::IsUserLoggedIn())
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
					<input type="text" id="' . self::$username . '" name="' . self::$username . '" value="' . \model\UsersModelDAL::GetLastLoginAttemptUsername() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
}