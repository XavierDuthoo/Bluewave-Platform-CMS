<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class SettingDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function writeSetting($key, $val) {
               $sql = "INSERT INTO settings(sleutel, val) VALUES(:key, :val)";
               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':key', $key);
               $statement->bindValue(':val', $val);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('kon de settings niet opslaan');
          }

          public function getAllSettings() {
               $sql = 'SELECT * FROM settings';
               $statement = $this->dbh->prepare($sql);

               if($statement->execute()) {
                    $settings = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $settings;
               }

               throw new Exception('Kon niet de settings ophalen');
          }

          public function createSettingsTable() {
               $sql = 'CREATE TABLE settings(id INT AUTO_INCREMENT, 
                                          sleutel varchar(255), 
                                          val varchar(255), PRIMARY KEY (id))';

               $statement = $this->dbh->prepare($sql);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('[SettingsDAO:createSettingsTable] Could not create settings table');
          }

     }
