<?php
require_once WWW_ROOT . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

class ControllerDAO {
    public $dbh;

    public $user        = 'admin';
    public $pass        = 'kaiser34';
    public $site        = 'default';
    public $baseurl     = 'https;//controller2.xd-it.be:8443';
    public $is_loggedin = false;
    public $debug       = true;
    private $cookies    = '';

    public function __construct($user, $pass, $site) {
        $this->dbh = DatabasePDO::getInstance();

        if(!empty($site)) $this->site = $site;
        if(!empty($user)) $this->user = $user;
        if(!empty($pass)) $this->pass = $pass;
        if(!empty($baseurl)) $this->baseurl = $baseurl;
    }

    public function login() {
        $this->cookies = '';

        $ch = $this->get_curl_obj();
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $this->baseurl."/login");
        curl_setopt($ch, CURLOPT_POSTFIELDS,"login=login&username=seasons"."&password=knokke");

        $content = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($content, 0, $header_size);
        $body = trim(substr($content, $header_size));
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        preg_match_all('|Set-Cookie: (.*);|U', $header, $results);

        return $this->baseurl;

        if(isset($results[1])) {
            $this->cookies = implode(';', $results[1]);
            if (strlen($body) < 10) {
                if (($code >= 200) && ($code < 400)) {
                    if (strpos($this->cookies,"unifises") !== FALSE) {
                        $this->is_loggedin = true;
                    }
                }
            }
        }

        return $this->is_loggedin;
    }

    public function getCompletedPolls($patientID) {
        $sql = 'SELECT test_index FROM test_results WHERE patient_number = :patient_id GROUP BY test_index';
        $statement = $this->dbh->prepare($sql);

        $statement->bindValue(':patient_id', $patientID);


        if($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
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
      if ($this->cookies != "") {
         curl_setopt($ch, CURLOPT_COOKIE,  $this->cookies);
      }
      return $ch;
   }
}