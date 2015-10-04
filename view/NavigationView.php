<?php

namespace view;


class NavigationView {

    private $appController, $auth;

    // Constructor
    public function __construct($appController, $auth) {

        // Store Application controller reference
        $this->appController = $appController;

        // Store auth model reference
        $this->auth = $auth;
    }

    // Private methods
    private function GetNavigationLinkOutput() {

        if($this->UserWantsToRegister()) {
            return '<a href="?">Back to login</a>';
        } else {
            return '<a href="?register">Register a new user</a>';
        }
    }

    private function GetLoggedInOutput() {
        return ($this->auth->IsUserLoggedIn() && !$this->auth->isSessionHijacked() ? '<h2>Logged in</h2>' : '<h2>Not logged in</h2>');
    }

    // Public methods
    public function UserWantsToRegister() {

        return isset($_GET['register']);
    }

    public function GetOutput() {

        $output = '';

        // Get logged in header text
        $output .= $this->GetLoggedInOutput();

        // Get navigation output
        $output .= $this->GetNavigationLinkOutput();

        return $output;
    }
}