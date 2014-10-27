<?php

    require_once WWW_ROOT . 'Controller' .DIRECTORY_SEPARATOR. 'AppController.php';

     class StartController extends AppController {

          public function __construct() {
               parent::__construct();
          }

          public function filter() {
               switch($this->action) {
                    default: return $this->index();
               }
          }

          public function index() {
               $subpage = 'accesspoints';

               if(!empty($_GET['subpage'])) {
                    $subpage = $_GET['subpage'];
               }

               switch($subpage) {
                    case 'accesspoints':
                         $content = $this->smarty->fetch("pages/accesspoints.tpl");
                         break;
                    case 'livestats':
                         $content = $this->smarty->fetch("pages/dashboard_subpages/livestats.tpl");
                         break;
                    case 'users':
                         $content = $this->smarty->fetch("pages/dashboard_subpages/users.tpl");
                         break;
                    default:
                         $content = $this->smarty->fetch("pages/accesspoints.tpl");
                         break;
               }
               
               $this->smarty->assign("content", $content);
          }

     }