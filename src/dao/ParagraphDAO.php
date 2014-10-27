<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class ParagraphDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function getAll($site_id) {
               $sql = 'SELECT * FROM sites_paragraphs WHERE site_id = :site_id ORDER BY order_number ASC';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetchAll();
               }
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_paragraphs(site_id, title, title_color, content, order_number, created_by, created, modified) VALUES(:site_id, :title, :title_color, :content, :order_number, :created_by, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':title', $post['title']);
               $statement->bindValue(':title_color', $post['title_color']);
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':order_number', 0);
               $statement->bindValue(':created_by', $user_id);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add paragraph');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_paragraphs SET title = :title, title_color = :title_color, content = :content, order_number = :order_number, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':title', $post['title']);
               $statement->bindValue(':title_color', $post['title_color']);
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':order_number', $post['order_number']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));
               $statement->bindValue(':id', $post['id']);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit paragraph');
          }

          public function edit_order($new_order, $id) {
               $sql = 'UPDATE sites_paragraphs SET order_number = :order_number WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':order_number', $new_order);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit order for paragraph');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_paragraphs WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete paragraph');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_paragraphs WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete paragraphs with site_id');
               
          }

      }
