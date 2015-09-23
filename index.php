<?php

// Include files

# Settings
require_once('settings.php');

# Views
require_once('view/HTMLView.php');
require_once('view/FormView.php');

# Controllers
require_once('controller/AppBaseController.php');
require_once('controller/AppController.php');
require_once('controller/LoginController.php');

# Models
require_once('model/Exceptions.php');
require_once('model/User.php');
require_once('model/FlashMessage.php');
require_once('model/Auth.php');
require_once('model/UserClient.php');

# DAL models
require_once('model/DAL/DBBase.php');
require_once('model/DAL/Users.php');

# Create Application controller object
$app = new \controller\AppController();


