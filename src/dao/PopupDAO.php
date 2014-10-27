<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class PopupDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function get($site_id) {
               $sql = 'SELECT * FROM sites_popups WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetch();
               }

               throw new Exception('Could not get popup');
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_popups(site_id, active, content, created, modified) VALUES(:site_id, :active, :content, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':active', intval($post['active']));
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add popup');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_popups SET active = :active, content = :content, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':active', $post['active']);
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));
               $statement->bindValue(':id', $post['id']);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit popup');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_popups WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete popup');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_popups WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete popups with site_id');
               
          }

      }
