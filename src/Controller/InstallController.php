<?php

    require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'AppController.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'UserDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SettingDAO.php';

     class InstallController extends AppController {
          private $userDAO;
          private $settingDAO;

          public function __construct() {
               parent::__construct();
          }

          public function filter() {
               switch($this->action) {
                    case 'save': return $this->save();
                    default: return $this->index();
               }
          }

          public function save() {
               // Get database variables from form
               $host = $_POST["databaseHost"];
               $name = $_POST["databaseNaam"];
               $user = $_POST["databaseUser"];
               $pass = $_POST["databasePassword"];

               // Write config.php file with database variables
               $filecontent = '<?php class Config {const DB_TYPE = "mysql"; const DB_HOST = "'.$host.'"; const DB_NAME = "'.$name.'"; const DB_USER = "'.$user.'"; const DB_PASS = "'.$pass.'"; const SALT = "'.sha1(date('Y-m-d H:i:s')).'"; const DEVELOPMENT = true;} ?>';
               $fp = fopen("Classes/Config.php", "w");
               fwrite($fp, $filecontent);
               fclose($fp);

               // Get Config.php and create DAO classes
               require_once WWW_ROOT.'classes'.DIRECTORY_SEPARATOR.'Config.php';
               $this->userDAO = new UserDAO();
               $this->settingDAO = new SettingDAO();

               // TODO: Create user tables
               // CREATE TABLE Persons(PersonID int,LastName varchar(255),FirstName varchar(255),Address varchar(255),City varchar(255));
               $tables = $this->userDAO->isCreated('users');
               if(count($tables) == 0) {
                    // No tables found, create user and settings table
                    $this->userDAO->createUserTable();
                    $this->settingDAO->createSettingsTable();
               }

               // Add default user to database
               $post['firstname'] = 'Xavier';
               $post['lastname'] = 'Duthoo';
               $post['email'] = 'saf';
               $post['password'] = 'kaiser';
               $post['admin'] = 1;
               $this->userDAO->createUser($post);

               // Save settings to settingsDatabase 
               $this->settingDAO->writeSetting('equipment', $_POST['equipment']);
               $this->settingDAO->writeSetting('key', $_POST['key']);
               $this->settingDAO->writeSetting('userID', $_POST['userID']);

               header('Location: index.php?message=firstinit');
          }

          public function index() {
               $content = $this->smarty->fetch("pages/install.tpl");
               $this->smarty->assign('fullwidth', true);
               $this->smarty->assign("content", $content);
          }
     }