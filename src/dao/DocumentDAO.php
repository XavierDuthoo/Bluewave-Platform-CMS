<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class DocumentDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function getAll($site_id) {
               $sql = 'SELECT * FROM sites_documents WHERE site_id = :site_id ORDER BY order_number ASC';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetchAll();
               }
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_documents(site_id, cover, name, url, order_number, created_by, created, modified) VALUES(:site_id, :cover, :name, :url, :order_number, :created_by, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':cover', $post['cover']);
               $statement->bindValue(':name', $post['name']);
               $statement->bindValue(':url', $post['url']);
               $statement->bindValue(':order_number', 0);
               $statement->bindValue(':created_by', $user_id);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add document');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_documents SET cover = :cover, name = :name, order_number = :order_number, url = :url, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':cover', $post['cover']);
               $statement->bindValue(':name', $post['name']);
               $statement->bindValue(':order_number', $post['order_number']);
               $statement->bindValue(':url', $post['url']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));
               $statement->bindValue(':id', $post['id']);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit document');
          }

          public function edit_order($new_order, $id) {
               $sql = 'UPDATE sites_documents SET order_number = :order_number WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':order_number', $new_order);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit order for document');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_documents WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete document');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_documents WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete documents with site_id');
               
          }

      }
