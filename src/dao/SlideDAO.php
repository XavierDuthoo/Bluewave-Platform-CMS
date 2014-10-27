<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class SlideDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function getAll($slider_id) {
               $sql = 'SELECT * FROM sites_sliders_slides WHERE slider_id = :slider_id ORDER BY order_number ASC';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':slider_id', $slider_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetchAll();
               }
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_sliders_slides(site_id, slider_id, image, caption, created_by, created, modified) VALUES(:site_id, :slider_id, :image, :caption, :created_by, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':slider_id', $post['slider_id']);
               $statement->bindValue(':image', $post['image']);
               $statement->bindValue(':caption', $post['caption']);
               $statement->bindValue(':created_by', $user_id);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add slide');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_sliders_slides SET image = :image, caption = :caption, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':image', $post['image']);
               $statement->bindValue(':caption', $post['caption']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit slide');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_sliders_slides WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete slide');
          }

          public function deleteForSlider($slider_id) {
               $sql = 'DELETE FROM sites_sliders_slides WHERE slider_id = :slider_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':slider_id', $slider_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete slides for slider');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_sliders_slides WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete slides for site');
               
          }

      }
