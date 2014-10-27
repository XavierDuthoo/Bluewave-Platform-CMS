<?php

    require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'AppController.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SettingDAO.php';

     class StatsController extends AppController {
          private $settingDAO;

          public function __construct() {
               parent::__construct();
          }

          public function filter() {
               switch($this->action) {
                    default: return $this->index();
               }
          }

          public function index() {
               $content = $this->smarty->fetch("pages/stats.tpl");
               $this->smarty->assign("content", $content);
          }
     }
