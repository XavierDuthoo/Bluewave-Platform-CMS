<?php
     
     define('WWW_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
     session_start();

     include_once WWW_ROOT . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';
     set_error_handler('globalErrorHandler');
     set_exception_handler('globalExceptionHandler');

     require_once WWW_ROOT.'Classes'.DIRECTORY_SEPARATOR.'Config.php';

     // Checken als user id bestaat, indien niet op -1 zetten

     if(empty($_SESSION['bluewavePlatformUserID'])) {
          $_SESSION['bluewavePlatformUserID'] = -1;
     }     

     $page = 'home';

     if(!empty($_GET['page'])) {
          $page = $_GET['page'];
     }

     if($_SESSION['bluewavePlatformUserID'] == -1) {
          $page = 'login';
     }

     $controller = NULL;
     switch($page) {
          case 'sites':
               require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'SitesController.php';
               $controller = new SitesController();
               break;

          case 'stats':
               require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'StatsController.php';
               $controller = new StatsController();
               break;

          case 'start':
               require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'StartController.php';
               $controller = new StartController();
               break;

          default:
               require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'UserController.php';
               $controller = new UserController();
               break;
     }

     $controller->filter();
     $controller->render();