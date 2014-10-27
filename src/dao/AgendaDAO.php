<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class AgendaDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function getAll($site_id) {
               $sql = 'SELECT * FROM sites_agendas WHERE site_id = :site_id ORDER BY order_number ASC';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetchAll();
               }
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_agendas(site_id, name, order_number, created_by, created, modified) VALUES(:site_id, :name, :order_number, :created_by, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':name', $post['name']);
               $statement->bindValue(':order_number', 0);
               $statement->bindValue(':created_by', $user_id);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add agenda');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_agendas SET name = :name, order_number = :order_number, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':name', $post['name']);
               $statement->bindValue(':order_number', $post['order_number']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit agenda');
          }

          public function edit_order($new_order, $id) {
               $sql = 'UPDATE sites_agendas SET order_number = :order_number WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':order_number', $new_order);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit order for agenda');
               
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_agendas WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete agenda');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_agendas WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete agendas with site_id');
               
          }

      }
