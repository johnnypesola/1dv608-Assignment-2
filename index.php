<?php

// Include files
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

// Show errors from server. Turn off on public servers.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Create objects of views
$loginViewObj = new \view\LoginView();
$dateTimeViewObj = new \view\DateTimeView();
$layoutViewObj = new \view\LayoutView('Login Example', 'Assignment 2');


$layoutViewObj->render(false, $loginViewObj, $dateTimeViewObj);

