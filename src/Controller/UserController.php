<?php

     require_once WWW_ROOT . 'Controller' .DIRECTORY_SEPARATOR. 'AppController.php';
     require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'UserDAO.php';
     require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'RecoveryDAO.php';

     class UserController extends AppController {
          private $userDAO;

          public function __construct() {
               parent::__construct();
               $this->userDAO = new UserDAO();
               $this->recoveryDAO = new RecoveryDAO();
          }

          public function filter() {
               switch($this->action) {
                    case 'add': return $this->add();
                    case 'overview': return $this->overview();
                    case 'logout': return $this->logout();
                    case 'delete': return $this->delete();
                    case 'edit': return $this->edit();
                    case 'login': return $this->login();
                    case 'choose_pass': return $this->chooseNewPass();
                    case 'request_link': return $this->requestLink();
                    default: return $this->index();
               }
          }

          public function index() {
               if($_SESSION['bluewavePlatformUserID'] > -1) {
                    header('Location: index.php?page=start');
               }
               $content = $this->smarty->fetch("pages/login.tpl");

               $this->smarty->assign("login", true);
               $this->smarty->assign("content", $content);
          }

          public function add() {
               $this->checkLoggedIn();
               if(empty($_POST)) {
                    header('Location: index.php?page=users&action=overview');
               }

               # Check if user already exists
               if(!$this->userDAO->alreadyRegistered($_POST['email'])) {
                    # Not yet, create the user
                    $user_id = $this->userDAO->createUser($_POST);

                    # Get activation key
                    $key = $this->recoveryDAO->createActivationRecoveryKey($user_id);

                    if($key) {
                         # Email activation email to new user
                         $this->email($_POST['firstname'], $_POST['email'], $_SESSION['bluewaveFirstname'], $_POST['language'], $key);
                    }

                    $this->smarty->assign('userAdded', true);
               } else {
                    # User already exists, show error message
                    $this->smarty->assign('userExists', true);                    
               }

               return $this->overview();
          }

          public function edit() {
               $this->checkLoggedIn();
               # User is trying to save the new information
               if(!empty($_POST)) {
                    # if checkbox is not filled out it's not in the post
                    if(!isset($_POST['banned'])) $_POST['banned'] = 0;

                    # inverse banned because question is allow login? and database is banned
                    if($_POST['banned'] == 0) {
                         $_POST['banned'] = 1;
                    } else if($_POST['banned'] == 1) {
                         $_POST['banned'] = 0;
                    }

                    if(!empty($_POST['password'])) {
                         $this->userDAO->updateUserWithNewPassword($_POST);
                    } else {
                         $this->userDAO->updateUserWithoutNewPassword($_POST);
                    }

                    $this->smarty->assign('userEdited', true);
                    if($_SESSION["bluewaveAccountType"] == 'admin' || $_SESSION['bluewaveAccountType'] == 'superadmin') {
                         return $this->overview();
                    } else {
                         header("Location: index.php?page=users&action=edit&userID=" . $post['id'] . "&message=updated");
                    }
               }

               # ID of the user we try to edit
               $idOfUserWeWantToEdit = $_GET["id"];
               if(!($_SESSION["bluewaveAccountType"] != 'admin' || $_SESSION["bluewaveAccountType"] != 'superadmin')) {
                    header("Location: index.php?page=users&action=overview&id=" . $_SESSION["bluewavePlatformUserID"] . "&message=noAccess");
               }

               $user = $this->userDAO->getUserByID($idOfUserWeWantToEdit);
               $this->smarty->assign("user", $user);
               $content = $this->smarty->fetch("pages/edit_user.tpl");
               $this->smarty->assign("content", $content);
          }

          public function login() {
               $user = $this->userDAO->login($_POST);

               if($user != -1) {
                    $_SESSION['bluewavePlatformUserID'] = $user['id'];
                    $_SESSION['bluewaveAccountType'] = $user['type'];
                    $_SESSION['bluewaveFirstname'] = $user['firstname'];
                    $_SESSION['bluewaveLanguage'] = $user['language'];
                    $_SESSION['bluewaveAvatarPath'] = $user['image'];
                    $this->userDAO->updateLastLogin($user['id']);
                    header('Location: index.php?page=start');
               } else {
                    $this->smarty->assign('pass_wrong', true);
                    return $this->index();
               }
          }

          public function overview() {
               $this->checkLoggedIn();
               if($_SESSION["bluewaveAccountType"] != 'superadmin') {
                    header('Location: index.php?page=start&message=noaccess');
               }

               $users = $this->userDAO->getAllUsers();

               $this->smarty->assign("users", $users);
               $content = $this->smarty->fetch("pages/user_overview.tpl");
               $this->smarty->assign("content", $content);
          }

          public function delete() {
               $this->checkLoggedIn();
               $userID = $_GET["id"];
               if($userID != $_SESSION["bluewavePlatformUserID"]) {
                    $success = $this->userDAO->removeUserWithID($userID);
                    if(!empty($_GET['ajax'])) {
                         echo 'true';
                         die();
                    }
                    $this->smarty->assign('deleteSuccess', true);
                    return $this->overview();
               }
          }

          public function logout() {
               session_destroy();
               $_SESSION['bluewavePlatformUserID'] = -1;
               $_SESSION['bluewaveAccountType'] = null;
               $_SESSION['bluewaveFirstname'] = null;
               header('Location: index.php');
          }

          public function email($name, $sendto, $adder, $language, $key) {
               $headers_client = "MIME-Version: 1.0\r\n";
               $headers_client .= "Content-Type: text/html; charset=utf-8\r\n";
               $headers_client .= "Content-Transfer-Encoding: 8bit\r\n";
               $headers_client .= "From: =?UTF-8?B?". 'Blue Wave' ."?=  <noreply@bluewavemarketing.com>\r\n";
               $headers_client .= "X-Mailer: PHP/". phpversion();


               $subject = '';
               $email;

               switch($language) {
                    case 'fr':
                         $subject = 'Registration au platform Blue Wave';
                         $email = file_get_contents(WWW_ROOT . '/resources/registratie_fr.html');
                         break;

                    default:
                         $subject = 'Registratie Blue Wave platform';
                         $email = file_get_contents(WWW_ROOT . '/resources/registratie_nl.html');
                         break;
               }

               $email = str_replace('{{name}}', $name, $email);
               $email = str_replace('{{adder}}', $adder, $email);

               mail($sendto, $subject, $email, $headers_client);
          }

          public function emailRecoveryLink($name, $sendto, $language, $key) {
               $headers_client = "MIME-Version: 1.0\r\n";
               $headers_client .= "Content-Type: text/html; charset=utf-8\r\n";
               $headers_client .= "Content-Transfer-Encoding: 8bit\r\n";
               $headers_client .= "From: =?UTF-8?B?". 'Blue Wave' ."?=  <noreply@bluewavemarketing.com>\r\n";
               $headers_client .= "X-Mailer: PHP/". phpversion();


               $subject = '';
               $email;

               switch($language) {
                    case 'fr':
                         $subject = 'Reset ton password sur le platform du Blue Wave marketing';
                         $email = file_get_contents(Config::ROOT . '/resources/recovery_fr.html');
                         break;

                    default:
                         $subject = 'Nieuw wachtwoord Blue Wave marketing platform';
                         $email = file_get_contents(Config::ROOT . '/resources/recovery_nl.html');
                         break;
               }

               $email = str_replace('{{name}}', $name, $email);
               $email = str_replace('{{key}}', $key, $email);

               mail($sendto, $subject, $email, $headers_client);
          }

          # Choose new password
          public function chooseNewPass() {
               # No recovery key in url so no reason to be here...
               if(empty($_GET['key'])) header('Location:index.php?page=users&action=index');

               # Check if the key in the url is still valid
               $key_information = $this->recoveryDAO->checkIfRecoveryKeyIsStillValid($_GET['key']);
               if($key_information) {
                    # Valid key check if there was a post request to the server

                    if(!empty($_POST)) {
                         # Set key invalid
                         $user_id = $this->recoveryDAO->invalidateUsedKey($_GET['key']);

                         # Update password in user table
                         $this->userDAO->updatePassword($_POST['password'], $user_id);
                         $this->userDAO->activateUser($user_id);

                         # Allowed extensions for the avatar
                         $allowed_extensions = array('jpeg', 'jpg', 'png');

                         # Explode the filename to find out the extensions
                         $temp_name = explode(".", $_FILES['avatar']['name']);
                         $extension = end($temp_name);

                         # Check on mime type and extensions
                         if ((($_FILES["avatar"]["type"] == "image/jpeg")|| ($_FILES["avatar"]["type"] == "image/jpg")|| ($_FILES["avatar"]["type"] == "image/pjpeg")|| ($_FILES["avatar"]["type"] == "image/x-png")|| ($_FILES["avatar"]["type"] == "image/png")) && ($_FILES["avatar"]["size"] < 8388608) && in_array($extension, $allowed_extensions)) {
                              if ($_FILES["avatar"]["error"] == 0) {
                                   # OK
                                   move_uploaded_file($_FILES["avatar"]["tmp_name"], "img/users/" . $user_id . '.' . $extension);
                                   $this->userDAO->updateImageForUser($user_id, $user_id . '.' . $extension);
                              } else {
                                   # Error
                                   $error = $_FILES["avatar"]["error"];
                              }
                         }

                         $this->smarty->assign('pass_created', true);
                         return $this->index();
                    }

               } else {
                    # Key information = false so we redirect to login with error message...
                    $this->smarty->assign('invalid_key', true);
                    return $this->index();
               }

               $content = $this->smarty->fetch("pages/choose_pass.tpl");

               $this->smarty->assign("login", true);
               $this->smarty->assign("content", $content);
          }

          public function requestLink() {
               if(!empty($_POST)) {
                    $user = $this->userDAO->findUserByEmail($_POST['email']);
                    if(!$user) {
                         $this->smarty->assign('emailNotFound', true);
                    } else {
                         # Invalidate old links
                         $this->recoveryDAO->setAllLinksInvalidForUserID($user['id']);
                         $this->userDAO->updatePassword('adsfkl;lafsdjksdakjfas;kdlfjpqjefersafj;fdj;adf', $user['id']);

                         # Add fresh key to db
                         $key = $this->recoveryDAO->createActivationRecoveryKey($user['id']);

                         # Email the recovery email to the user
                         $this->emailRecoveryLink($user['firstname'], $user['email'], $user['language'], $key);

                         $this->smarty->assign('emailSend', true);
                    }
               }

               $content = $this->smarty->fetch("pages/request_link.tpl");
               $this->smarty->assign('login', true);
               $this->smarty->assign('content', $content);
          }

          # Function that checks if the user is logged in and sends them to the login page if not
          public function checkLoggedIn() {
               if(empty($_SESSION['bluewavePlatformUserID']) || $_SESSION['bluewavePlatformUserID'] < 0) {
                    header('Location: index.php?page=users&action=index&message=no_access');
               }
          }
     }