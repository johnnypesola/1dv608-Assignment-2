<?php

namespace view;


class RegistrationView {

// Init variables
    private $exceptions;

// Constructor
    public function __construct(\model\ExceptionsService $exceptions) {
        $this->exceptions = $exceptions;
    }

    // Private Methods
    private function GetRegisterFormOutput() {
        return '
            <div class="container" >

			<h2>Register new user</h2>
			<form action="?register" method="post" enctype="multipart/form-data">
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id="RegisterView::Message"></p>
					<label for="RegisterView::UserName" >Username :</label>
					<input type="text" size="20" name="RegisterView::UserName" id="RegisterView::UserName" value="" />
					<br/>
					<label for="RegisterView::Password">Password  :</label>
					<input type="password" size="20" name="RegisterView::Password" id="RegisterView::Password" value="" />
					<br/>
					<label for="RegisterView::PasswordRepeat" >Repeat password  :</label>
					<input type="password" size="20" name="RegisterView::PasswordRepeat" id="RegisterView::PasswordRepeat" value="" />
					<br/>
					<input id="submit" type="submit" name="DoRegistration"  value="Register" />
					<br/>
				</fieldset>
			</form>
        ';
    }

    // Public Methods
    public function GetHTML() {

        return $this->GetRegisterFormOutput();
    }

} 