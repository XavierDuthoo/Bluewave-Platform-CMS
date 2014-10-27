<?php

     require_once WWW_ROOT . 'api' . DIRECTORY_SEPARATOR . 'dao' . DIRECTORY_SEPARATOR . 'BluewaveDAO.php';
     require_once WWW_ROOT . 'Smarty-3.1.8'. DIRECTORY_SEPARATOR .'libs'. DIRECTORY_SEPARATOR .'Smarty.class.php';
     require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SitesDAO.php';     

     class AppController {

          protected $action = '';
          protected $smarty;

          public function __construct() {
               if(!empty($_GET['action'])) {
                    $this->action = $_GET['action'];
               }

               $bluewave = new bluewaveapi(null, null, null, 'seasons');

               $this->sitesDAO = new SitesDAO();

               require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SettingDAO.php';

               $this->smarty = new Smarty();
               $this->smarty->setCompileDir('smarty_compile');
               $this->smarty->setTemplateDir('smarty_templates');
               $this->smarty->muteExpectedErrors();
               
               $all_sites = $bluewave->cmd_sitemgr_sites();
               //var_dump($all_sites);

               $this->smarty->assign('all_sites', $all_sites);
          }

          public function filter() {

          }

          public function render() {
               $headerbar = $this->smarty->fetch("parts/header-bar.tpl");
               $leftmenu = $this->smarty->fetch("parts/left-menu.tpl");

               $this->smarty->assign('headerbar', $headerbar);
               $this->smarty->assign('leftmenu', $leftmenu);
               $this->smarty->display('index.tpl');
          }

     }

