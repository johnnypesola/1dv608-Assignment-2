<?php

// Include files

# App core
require_once('app.php');

# Views
require_once('view/PageView.php');
require_once('view/FormView.php');

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

