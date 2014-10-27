<?php
     define('WWW_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

     require_once WWW_ROOT . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';
     require_once WWW_ROOT . 'classes' . DIRECTORY_SEPARATOR . 'Config.php';
     require_once WWW_ROOT . 'dao' . DIRECTORY_SEPARATOR . 'BluewaveDAO.php';
     require_once(WWW_ROOT . 'Slim' . DIRECTORY_SEPARATOR . 'Slim.php');

     $app = new Slim();

     $app->post('/:site/login', 'login');
     $app->post('/:site/logout', 'logout');

     $app->post('/:site/authorize-guest', 'authorize_guest');
     $app->post('/:site/unauthorize-guest', 'unauthorize_guest');
     $app->post('/:site/authorize_all', 'authorize_all');
     $app->post('/:site/unauthorize_all', 'unauthorize_all');
     $app->post('/:site/reconnect-sta', 'reconnect_sta');
     $app->post('/:site/reconnectall-sta', 'reconnectall_sta');
     $app->post('/:site/block-sta', 'block_sta');
     $app->post('/:site/unblock-sta', 'unblock_sta');
     $app->post('/:site/upd/wlanconf', 'update_wlanconf');
     $app->post('/:site/set/settings', 'set_settings');
     $app->post('/:site/set/site', 'add_site');
     $app->post('/:site/remove/site', 'remove_site');
     $app->post('/:site/edit/site', 'edit_site');
     $app->post('/:site/stats/get_hourly', 'stat_hourly');
     $app->post('/:site/stats/get_daily', 'stat_daily');
     $app->post('/:site/stats/session', 'stat_session');

     $app->get('/:site/guests', 'list_guests');
     $app->get('/:site/alarm', 'list_alarm');
     $app->get('/:site/events', 'event_all');
     $app->get('/:site/users', 'all_users');
     $app->get('/:site/vouchers', 'get_vouchers');
     $app->get('/:site/settings', 'get_settings');

     $app->get('/:site/stats/device', 'stat_device');
     $app->get('/:site/stats/station', 'stat_sta');
     $app->get('/:site/stats/quicklook', 'stat_quicklook');

     $app->get('/:site/sysinfo', 'stat_sysinfo');

     $app->get('/:site/list/map', 'list_map');
     $app->get('/:site/list/usergroup', 'list_usergroup');
     $app->get('/:site/list/wlangroup', 'list_wlangroup');
     $app->get('/:site/list/wlanconf', 'list_wlanconf');

     $app->get('/:site/manager/sites', 'cmd_sitemgr_sites');
     $app->get('/:site/manager/admins', 'cmd_sitemgr_admins');

     $app->post('/:site/create-voucher', 'create_voucher');

     $app->run();

     # POST
     # Returns true is user is sucessfully logged in and false if login failed
     function login($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);

          if($bluewave->login() == true) {
               $_SESSION['bluewaveIsLoggedIn'] = true;
               var_dump($_SESSION);
               return true;
          } else {
               var_dump($_SESSION);
               return false;
          }

     }

     # POST
     # Returns true if guest is logged out
     function logout($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          return $bluewave->logout();
     }

     function authorize_all($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->authorize_all());
     }

     function unauthorize_all($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->unauthorize_all());
     }

     # POST ('mac' and 'minutes')
     # Returns true if guest is re-authorized
     function authorize_guest($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->authorize_guest($post['mac'], $post['minutes']));
     }

     # POST ('mac')
     # Returns true is guest is une-authorized
     function unauthorize_guest($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->unauthorize_guest($post['mac']));
     }

     # POST ('mac')
     # Returns true is station is reconnected
     function reconnect_sta($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->reconnect_sta($post['mac']));
     }

     function reconnectall_sta($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->reconnectall_sta());
     }

     # POST ('mac')
     # Returns true is station is reconnected
     function block_sta($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->block_sta($post['mac']));
     }

     # POST ('mac')
     # Returns true is station is reconnected
     function unblock_sta($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->unblock_sta($post['mac']));
     }

     # POST
     # Returns json with true or false
     function update_wlanconf($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->update_wlanconf());

          return;
     }

     # GET
     # Returns json with guests
     function list_guests($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_guests());

          return;
     }

     # GET
     # Returns json with alarms
     function list_alarm($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_alarm());

          return;
     }

     # GET
     # Returns json with alarms
     function event_all($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->event_all());

          return;
     }

     # GET
     # Returns json with alarms
     function all_users($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->all_users());

          return;
     }

     # POST
     # Returns json with alarms
     function add_site($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->add_site());

          return;
     }

     # POST
     # Returns json with alarms
     function remove_site($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->remove_site());

          return;
     }

     # POST
     # Returns json with alarms
     function edit_site($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->edit_site());

          return;
     }

     # GET
     # Returns json with settings
     function get_settings($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->get_settings());

          return;
     }

     # POST
     # Sets json with settings
     function set_settings($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->set_settings());

          return;
     }

     # GET
     # Returns json with vouchers
     function get_vouchers($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->get_vouchers());

          return;
     }

     # GET
     # Returns json with stats device
     function stat_device($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->stat_device());

          return;
     }

     # POST
     # Returns json with daily access point stats
     function stat_daily($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->get_daily());

          return;
     }

     # POST
     # Returns json with daily access point stats
     function stat_hourly($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->get_hourly());

          return;
     }

     # POST
     # Returns json with stats station
     function stat_session($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->stat_session());

          return;
     }

     # GET
     # Returns json with stats station
     function stat_sta($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->stat_sta());

          return;
     }

     # GET
     # Returns json with station systeminfo
     function stat_sysinfo($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->stat_sysinfo());

          return;
     }

     # GET
     # Returns json with station systeminfo
     function stat_quicklook($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->stat_quicklook());

          return;
     }

     # GET
     # Returns json with list of maps
     function list_map($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_map());

          return;
     }

     # GET
     # Returns json with list of usergroup
     function list_usergroup($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_usergroup());

          return;
     }

     # GET
     # Returns json with list with wlan groups
     function list_wlangroup($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_wlangroup());

          return;
     }

     # GET
     # Returns json with wlan configuration
     function list_wlanconf($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->list_wlanconf());

          return;
     }

     # GET
     # Returns json with list of sites
     function cmd_sitemgr_sites($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->cmd_sitemgr_sites());

          return;
     }

     # GET
     # Returns json with list of admins
     function cmd_sitemgr_admins($site) {
          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->cmd_sitemgr_admins());

          return;
     }

     # POST ('minutes', 'number_of_vouchers_to_create', 'note', 'up', 'down', 'Mbytes'
     # Returns array of vouchers code
     function create_voucher($site) {
          $request = Slim::getInstance()->request();
          $post = $request->post();

          $bluewave = new bluewaveapi(null, null, null, $site);
          echo json_encode($bluewave->create_voucher($post['minutes'], $post['number_of_vouchers_to_create'], $post['note'], $post['up'], $post['down'], $post['Mbytes']));
     }
