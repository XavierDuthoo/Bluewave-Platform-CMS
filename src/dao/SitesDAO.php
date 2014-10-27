<?php

     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class SitesDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          # Get all sites from the database
          # If successful returns array with all sites
          public function getAllSites() {
               $sql = 'SELECT * FROM sites';

               $statement = $this->dbh->prepare($sql);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    $sites = $statement->fetchAll();
                    return $sites;
               }

               throw new Exception('Could not fetch sites');
          }

          public function getMySites() {
               // for user restricted with only own site(s)
               // misschien  al opslaan in sessie ;)
          }

          public function getSiteByID($site_id) {
               /*$sql = 'SELECT * FROM sites WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    $site = $statement->fetch();
                    return $site;
               }*/

               throw new Exception('Cound not fetch sites', 1);
          }

          public function getUsersForSite($site_id) {
               $sql = 'SELECT users.*, sites_and_users.admin, sites_and_users.created_by, sites_and_users.created FROM users LEFT JOIN sites_and_users ON sites_and_users.user_id = users.id WHERE sites_and_users.site_id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    $users = $statement->fetchAll();
                    return $users;
               }

               throw new Exception('Cound not fetch users for site', 1);
          }

          public function addSite($post) {
               $sql = 'INSERT INTO sites(name, identifier, url, created, modified) VALUES(:name, :identifier, :url, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':name', $post['sitename']);
               $statement->bindValue(':identifier', $post['identifier']);
               $statement->bindValue(':url', $post['url']);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add application');

          }
          public function edit($post) {
               $sql = 'UPDATE sites SET name = :name, identifier = :identifier, url = :url, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':name', $post['sitename']);
               $statement->bindValue(':identifier', $post['identifier']);
               $statement->bindValue(':url', $post['url']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));
               $statement->bindValue(':id', $post['id']);


               if($statement->execute()) {
                    return true;
               }
          }

          public function deleteSite($site_id) {
               $sql = 'DELETE FROM sites WHERE id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete application');
          }

     }
