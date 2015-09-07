<?php

// Include files

# App core
require_once('app.php');

# Views
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

# Controllers
require_once('controller/LoginController.php');
require_once('controller/ValidationController.php');

# Models
require_once('model/LoginAttemptModel.php');
require_once('model/ErrorArrayModel.php');
require_once('model/UserModel.php');

# DAL models
require_once('model/DAL/UsersModelDAL.php');

$App = new App();

// Process login, if there has been a login
$App->loginControllerObj->processLogin();

//$loginViewObj->getLoginAttempt();

$App->layoutViewObj->render($App->loginViewObj, $App->dateTimeViewObj);

