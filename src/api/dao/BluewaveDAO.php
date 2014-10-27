<?php

ini_set('memory_limit', '-1');

if (strpos(WWW_ROOT, 'api')) {
   require_once WWW_ROOT . 'classes' . DIRECTORY_SEPARATOR . 'APIDatabasePDO.php';
} else {
   require_once WWW_ROOT . 'api' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'APIDatabasePDO.php';
}

Class bluewaveapi {

   public $user="admin";
   public $password="kaiser34";
   public $site="seasons";
   public $baseurl="https://controller2.xd-it.be:8443";
   public $is_loggedin=false;
   private $cookies="";
   public $debug=true;
   private $instancedb;

   function __construct($user, $password, $baseurl, $site) {
      if (isset($_POST["site"]) || !empty($_POST["site"])) {
         $this->site = $_POST["site"];
      } else {
         $this->site = $site;
      }

      if(empty($_SESSION['bluewaveCookies'])) {
         $_SESSION['bluewaveCookies']="";
      }

      if (!empty($user)) $this->user = $user;
      if (!empty($password)) $this->password = $password;
      if (!empty($baseurl)) $this->baseurl = $baseurl;

      $this->dbh = APIDatabasePDO::getInstance();
   }

   /*
   Login to unifi Controller
   */

   public function login() {
      //echo 'login';
      $_SESSION['bluewaveCookies']="";
      $ch=$this->get_curl_obj();
      curl_setopt($ch, CURLOPT_HEADER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $this->baseurl."/login");
      curl_setopt($ch, CURLOPT_POSTFIELDS,"login=login&username=".$this->user."&password=".$this->password);
      $content=curl_exec($ch);
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($content, 0, $header_size);
      $body = trim(substr($content, $header_size));
      $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close ($ch);
      preg_match_all('|Set-Cookie: (.*);|U', $header, $results);
      if (isset($results[1])) {
         $_SESSION['bluewaveCookies'] = implode(';', $results[1]);
         if (strlen($body) < 10) {
            if (($code >= 200) && ($code < 400)) {
               if (strpos($_SESSION['bluewaveCookies'],"unifises") !== FALSE) {
                  $_SESSION['bluewaveIsLoggedIn'] = true;
               }
            }
         }
      }

      return $_SESSION['bluewaveIsLoggedIn'];
   }

   /*
   Logout from unifi Controller
   */
   public function logout() {
      if (!$_SESSION['bluewaveIsLoggedIn']) return false;
      $return=true;
      $content=$this->exec_curl($this->baseurl."/logout");
      $_SESSION['bluewaveIsLoggedIn'] = false;
      $_SESSION['bluewaveCookies']="";
      return $return;
   }

   /*
   Authorize a mac address
   paramater <mac address>,<minutes until expires from now>
   return true on success
   */
   public function authorize_all() {
      $this->login();
      $return=false;
      $alldata = $this->stat_sta();

      foreach ($alldata as $data) {
         if (!$data->authorized) {
            $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'authorize-guest', 'mac':'".$data->mac."', 'minutes':'".$_POST['minutes']."'}");
            $content_decoded=json_decode($content);
            if (isset($content_decoded->meta->rc)) {
               if ($content_decoded->meta->rc == "ok") {
                  $return=true;
               }
            }
         }
      }
      return $return;
   }

   /*
   unauthorize a mac address
   paramater <mac address>
   return true on success
   */
   public function unauthorize_all() {
      $this->login();
      $return=false;
      $alldata = $this->stat_sta();

      foreach ($alldata as $data) {
         if ($data->authorized) {
            $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'unauthorize-guest', 'mac':'".$data->mac."'}");
            $content_decoded=json_decode($content);
            if (isset($content_decoded->meta->rc)) {
               if ($content_decoded->meta->rc == "ok") {
                  $return=true;
               }
            }
         }
      }
      return $return;
   }

   /*
   Authorize a mac address
   paramater <mac address>,<minutes until expires from now>
   return true on success
   */
   public function authorize_guest($mac,$minutes) {
      $mac=strtolower($mac);
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'authorize-guest', 'mac':'".$mac."', 'minutes':".$minutes."}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   /*
   unauthorize a mac address
   paramater <mac address>
   return true on success
   */
   public function unauthorize_guest($mac) {
      $mac=strtolower($mac);
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'unauthorize-guest', 'mac':'".$mac."'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   /*
   reconnect a client
   paramater <mac address>
   return true on success
   */
   public function reconnect_sta($mac) {
      $mac=strtolower($mac);
      $this->login();
      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'kick-sta', 'mac':'".$mac."'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   public function reconnectall_sta() {
      $this->login();
      $return=false;
      $alldata = $this->stat_sta();

      foreach ($alldata as $data) {
         if ($data->authorized) {
            $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'cmd':'kick-sta', 'mac':'".$data->mac."'}");
            $content_decoded=json_decode($content);
            if (isset($content_decoded->meta->rc)) {
               if ($content_decoded->meta->rc == "ok") {
                  $return=true;
               }
            }
         }
      }
      return $return;
   }

   /*
   block a client
   paramater <mac address>
   return true on success
   */
   public function block_sta($mac) {
      $mac=strtolower($mac);
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || $_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'mac':'".$mac."', 'cmd':'block-sta'}");

      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   /*
   unblock a client
   paramater <mac address>
   return true on success
   */
   public function unblock_sta($mac) {
      $mac=strtolower($mac);
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || $_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/stamgr","json={'mac':'".$mac."', 'cmd':'unblock-sta'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   /*
   list guests
   returns an array of guest objects
   */
   public function list_guests() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/guest","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $guest) {
                  $return[]=$guest;
               }
            }
         }
      }
      return $return;
   }
   public function list_alarm() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/list/alarm","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $alarm) {
                  $return[]=$alarm;
               }
            }
         }
      }
      return $return;
   }
   public function event_all() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/event","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $alarm) {
                  $return[]=$alarm;
               }
            }
         }
      }
      return $return;
   }
   public function all_users() {
      $return = array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();

      $sql = "SELECT * FROM all_users WHERE site=:site";

      $statement = $this->dbh->prepare($sql);
      $statement->bindValue(':site', $this->site);

      if($statement->execute()) {
            while ($row = $statement->fetchAll(PDO::FETCH_ASSOC)) {
               $return = $row;
            }
      }
      return $return;
   }
   /*
   list vouchers
   returns a array of voucher objects
   */
   public function get_vouchers($create_time="") {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $json="";
      if (trim($create_time) != "") {
         $json.="'create_time':".$create_time."";
      }
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/voucher","json={".$json."}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $voucher) {
                  $return[]=$voucher;
               }
            }
         }
      }
      return $return;
   }
   public function get_settings() {
      $return=array();
      $this->login();
      //if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) ;

      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/get/setting","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function set_settings() {
      $return=false;
      $this->login();
      //if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) ;

      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/set/setting/guest_access","json={'redirect_url': '" . $_POST['redirect_url'] . "'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }

   public function stat_device() {
      $return=array();

      //if (!isset($_SESSION['bluewaveIsLoggedIn']) || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/device","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function stat_session() {
      $return=array();
      $this->login();

      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/session","json={'type':'all','start':'" . $_POST['start'] . "','end':'" . $_POST['einde'] . "'}");
      $content_decoded=json_decode($content);

      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function stat_sta() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/sta","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function stat_sysinfo() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/sysinfo","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function list_map() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/list/map","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function list_usergroup() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/list/usergroup","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function list_wlangroup() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/list/wlangroup","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function list_wlanconf() {
      $return=array();
      $this->login();

      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/list/wlanconf","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function update_wlanconf() {
      $this->login();

      $return=false;
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/upd/wlanconf/" . $_POST['wlan_id'],"json={'name': '". $_POST['new_name'] . "'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return=true;
         }
      }
      return $return;
   }
   public function cmd_sitemgr_sites() {
      $return=array();
      $this->login();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/sitemgr","json={'cmd':'get-sites'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function add_site() {
      $return=false;
      $this->login();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/sitemgr","json={'name':'" . $_POST['name'] . "','desc':'" . $_POST['desc'] . "','cmd':'add-site'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return = true;
         }
      }
      return $return;
   }
   public function remove_site() {
      $return=false;
      $this->login();

      $content=$this->exec_curl($this->baseurl."/api/s/" . $_POST['site'] . "/cmd/sitemgr","json={'cmd':'delete-site','site':'" . $_POST['id'] . "'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return = true;
         }
      }
      return $return;
   }
   public function edit_site() {
      $return=false;
      $this->login();

      $content=$this->exec_curl($this->baseurl."/api/s/" . $_POST['identifier'] . "/cmd/sitemgr","json={'cmd':'update-site','desc':'" . $_POST['newname'] . "'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            $return = true;
         }
      }
      return $return;
   }
   public function get_daily() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $date = date_create();

      $timestampend = $_POST['timestamp'];
      $timestampstart = $timestampend + 2629743000;

      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/report/daily.system",'json={"attrs":["bytes","num_sta","time"],"start":' . number_format($timestampend, 0,'','') . ',"end":'. number_format($timestampstart, 0,'','') .'}');
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function get_hourly() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();

      $timestampstart = $_POST['timestamp'];
      $timestampend = $timestampstart - 86400000;

      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/report/hourly.system",'json={"attrs":["bytes","num_sta","time"],"start":' . number_format($timestampend, 0,'','') . ',"end":'. number_format($timestampstart, 0,'','') .'}');
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function cmd_sitemgr_admins() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/sitemgr","json={'cmd':'get-admins'}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   public function stat_quicklook() {
      $return=array();
      if (!isset($_SESSION['bluewaveIsLoggedIn'])  || !$_SESSION['bluewaveIsLoggedIn']) $this->login();
      $return=array();
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/stat/quicklook","json={}");
      $content_decoded=json_decode($content);
      if (isset($content_decoded->meta->rc)) {
         if ($content_decoded->meta->rc == "ok") {
            if (is_array($content_decoded->data)) {
               foreach ($content_decoded->data as $users) {
                  $return[]=$users;
               }
            }
         }
      }
      return $return;
   }
   /*
   unblock a client
   paramater <minutes>,<number_of_vouchers_to_create>,<note>,<up>,<down>,<mb>
   returns a array of vouchers codes (Note: without the "-" in the middle)
   */
   public function create_voucher($minutes,$number_of_vouchers_to_create=1,$note="",$up=0,$down=0,$Mbytes=0) {
      $return=array();
      if (!$_SESSION['bluewaveIsLoggedIn']) return $return;
      $json="'cmd':'create-voucher','expire':".$minutes.",'n':".$number_of_vouchers_to_create."";
      if (trim($note) != "") {
         $json.=",'note':'".$note."'";
      }
      if ($up > 0) {
         $json.=",'up':".$up."";
      }
      if ($down > 0) {
         $json.=", 'down':".$down."";
      }
      if ($Mbytes > 0) {
         $json.=", 'bytes':".$Mbytes."";
      }
      $content=$this->exec_curl($this->baseurl."/api/s/".$this->site."/cmd/hotspot","json={".$json."}");
      $content_decoded=json_decode($content);
      if ($content_decoded->meta->rc == "ok") {
         if (is_array($content_decoded->data)) {
            $obj=$content_decoded->data[0];
            foreach ($this->get_vouchers($obj->create_time) as $voucher)  {
               $return[]=$voucher->code;
            }
         }
      }
      return $return;
   }

   private function exec_curl($url,$data="") {
      $ch=$this->get_curl_obj();
      curl_setopt($ch, CURLOPT_URL, $url);
      if (trim($data) != "") {
         curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
      } else {
         curl_setopt($ch, CURLOPT_POST, FALSE);
      }
      $content=curl_exec($ch);
      // if ($this->debug == true) {
      //    print "---------------------\n<br>\n";
      //    print $url."\n<br>\n";
      //    print $data."\n<br>\n";
      //    print "---------------------\n<br>\n";
      //    print $content."\n<br>\n";
      //    print "---------------------\n<br>\n";
      //    }
      curl_close ($ch);
      return $content;
   }

   private function get_curl_obj() {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSLVERSION, 3);
      curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);
      if ($this->debug == true) {
         curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
      }
      if ($_SESSION['bluewaveCookies'] != "") {
         curl_setopt($ch, CURLOPT_COOKIE,  $_SESSION['bluewaveCookies']);
      }
      return $ch;
   }

}
