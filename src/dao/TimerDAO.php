<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class TimerDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function get($site_id) {
               $sql = 'SELECT * FROM sites_timers WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetch();
               }

               throw new Exception('Could not get popup');
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_timers(site_id, active, seconds, created, modified) VALUES(:site_id, :active, :seconds, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':active', $post['active']);
               $statement->bindValue(':seconds', $post['seconds']);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add timer');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_timers SET active = :active, seconds = :seconds, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':active', intval($post['active']));
               $statement->bindValue(':seconds', $post['seconds']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));
               $statement->bindValue(':id', $post['id']);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit timer');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_timers WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete timer');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_timers WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete timers with site_id');
               
          }

      }
