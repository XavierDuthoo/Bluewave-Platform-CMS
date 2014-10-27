<?php
    
    require_once WWW_ROOT . 'Controller' . DIRECTORY_SEPARATOR . 'AppController.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SettingDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'AgendaDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'DocumentDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'EventDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'ParagraphDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'PopupDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SlideDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SliderDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'TimerDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'UserDAO.php';
    require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'SitesDAO.php';
    require_once WWW_ROOT . 'api' . DIRECTORY_SEPARATOR . 'dao' . DIRECTORY_SEPARATOR . 'BluewaveDAO.php';

    class SitesController extends AppController {
        private $settingDAO;

        public function __construct() {
            parent::__construct();
            $this->agendaDAO = new AgendaDAO();
            $this->documentDAO = new DocumentDAO();
            $this->eventDAO = new EventDAO();
            $this->paragraphDAO = new ParagraphDAO();
            $this->popupDAO = new PopupDAO();
            $this->slideDAO = new SlideDAO();
            $this->sliderDAO = new SliderDAO();
            $this->timerDAO = new TimerDAO();
            $this->userDAO = new TimerDAO();
            $this->sitesDAO = new SitesDAO();
        }

        public function filter() {
            switch($this->action) {
                case 'view': return $this->view();
                case 'editinfo' : return $this->editinfo();
                case 'delete': return $this->delete();
                case 'getBlock': return $this->getBlock();
                case 'addBlock': return $this->addBlock();
                case 'editBlock': return $this->editBlock();
                case 'deleteBlock': return $this->deleteBlock();
                default: return $this->index();
            }
        }

        public function index() {
            $content = $this->smarty->fetch("pages/sites.tpl");
            $this->smarty->assign("content", $content);
        }

        public function view() {
            # Check if we have a site with this ID
            if(empty($_GET['id'])) header('Location: index.php?page=sites');

            if(!empty($_GET['id'])) {
                # Get site information for ID
                //$site = $this->sitesDAO->getSiteByID($_GET['id']);
                $values = array();

                $values['id'] = $_GET['id'];
                $values['name'] = $_GET['name'];                
                $bluewave = new bluewaveapi(null, null, null, $values['name']);

                # If site not equal to false add users and default blocks for site otherwise redirect            
                $values['name'] ? $values['no_sort']['popup']   = $this->popupDAO->get($values['id']) : header('Location:index.php?page=sites');
                $values['name'] ? $values['no_sort']['timer']   = $this->timerDAO->get($values['id']) : header('Location:index.php?page=sites');
                $values['name'] ? $values['no_sort']['users']   = $this->sitesDAO->getUsersForSite($values['id']) : header('Location:index.php?page=sites');
                $values['name'] ? $values['no_sort']['settings'] = $bluewave->get_settings() : header('Location:index.php?page=sites');
                $values['name'] ? $values['no_sort']['ssids']  = $bluewave->list_wlanconf() : header('Location:index.php?page=sites');
                //$site ? $site['no_sort']['sites']  = $bluewave->cmd_sitemgr_sites() : header('Location:index.php?page=sites');

                # Get all other elements
                include 'includes/get_all_blocks.php';
            }

            $this->smarty->assign('site', $values);
            $content = $this->smarty->fetch('pages/site_view.tpl');
            $this->smarty->assign('content', $content);
        }

        public function editinfo() {
            if(!empty($_POST)) {
                if($this->sitesDAO->edit($_POST)) {
                    header('Location: index.php?page=sites&message=editcomplete');
                }
            }

            $site['id'] = $_GET['id'];
            $site['name'] = $_GET['name'];
            $site['identifier'] = $_GET['identifier'];

            $this->smarty->assign("site" , $site);
            $content = $this->smarty->fetch('pages/edit_info.tpl');
            $this->smarty->assign('content', $content);

        }

        public function delete() {
            if(!empty($_GET['id'])) {
                if($this->sitesDAO->deleteSite($_GET['id'])) {
                    include 'includes/delete_all_blocks.php';
                    if($deleted) {
                        if(!empty($_GET['ajax'])) {
                            echo 'true';
                            die();
                        }
                        return $this->index();
                    }
                }
            }

            return $this->index();
        }

        public function getBlock() {
            $content = $this->smarty->fetch('parts/' . $_GET['context'] . '.tpl');
            echo $content;
            die();
        }

        public function addBlock() {
            switch($_POST['context']) {
                case 'paragraph':
                    $insert_id = $this->paragraphDAO->add($_POST, $_SESSION['bluewavePlatformUserID']);
                    break;

                case 'document':
                    $insert_id = $this->documentDAO->add($_POST, $_SESSION['bluewavePlatformUserID']);
                    break;

                case 'agenda':
                    $insert_id = $this->agendaDAO->add($_POST, $_SESSION['bluewavePlatformUserID']);
                    break;

                case 'event':
                    $insert_id = $this->eventDAO->add($_POST, $_SESSION['bluewavePlatformUserID']);
                    break;
            }

            echo $insert_id; die();
        }

        public function editBlock() {
            switch($_POST['context']) {
                case 'paragraph':
                    $updated = $this->paragraphDAO->edit($_POST);
                    break;

                case 'document':
                    $updated = $this->documentDAO->edit($_POST);
                    break;
            }

            echo $updated; die();
        }

        public function deleteBlock() {
            switch($_POST['context']) {
                case 'paragraph':
                    $deleted = $this->paragraphDAO->delete($_POST['id']);
                    break;

                case 'document':
                    $deleted = $this->documentDAO->delete($_POST['id']);
                    break;

                case 'agenda':
                    $this->eventDAO->deleteForAgenda($_POST['id']);
                    $deleted = $this->agendaDAO->delete($_POST['id']);
                    break;
            }

            echo $deleted; die();
        }
    }
