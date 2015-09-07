<?php

// Include files

# Views
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

# Controllers
require_once('controller/LoginController.php');
require_once('controller/ValidationController.php');

# Models
require_once('model/LoginAttemptModel.php');
require_once('model/UserModel.php');

# DAL models
require_once('model/DAL/UsersModelDAL.php');

// Error settings

// Show errors from server. Turn off on public servers.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Create objects of views
$loginViewObj = new \view\LoginView();
$dateTimeViewObj = new \view\DateTimeView();
$layoutViewObj = new \view\LayoutView('Login Example', 'Assignment 2');

$layoutViewObj->render($loginViewObj, $dateTimeViewObj);

