<?php

// Show errors from server. Turn off on public servers.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Include files
# Views
require_once('view/BaseView.php');
require_once('view/HTMLView.php');
require_once('view/FormView.php');

# Controllers
require_once('controller/AppBaseController.php');
require_once('controller/AppController.php');
require_once('controller/LoginController.php');

# Models
require_once('model/LoginAttempt.php');
require_once('model/ErrorArray.php');
require_once('model/User.php');

# DAL models
require_once('model/DAL/DBBase.php');
require_once('model/DAL/Users.php');

# Create Application controller object
$app = new \controller\AppController();

