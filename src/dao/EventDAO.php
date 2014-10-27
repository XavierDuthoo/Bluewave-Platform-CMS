<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class EventDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          public function getAll($agenda_id) {
               $sql = 'SELECT * FROM sites_agendas_events WHERE agenda_id = :agenda_id ORDER BY UNIX_TIMESTAMP(start_date) ASC';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':agenda_id', $agenda_id);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    return $statement->fetchAll();
               }
          }

          public function add($post, $user_id) {
               $sql = 'INSERT INTO sites_agendas_events(site_id, agenda_id, event_title, start_date, end_date, image, content, fb_url, created_by, created, modified) VALUES(:site_id, :agenda_id, :event_title, :start_date, :end_date, :image, :content, :fb_url, :created_by, :created, :modified)';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $post['site_id']);
               $statement->bindValue(':agenda_id', $post['agenda_id']);
               $statement->bindValue(':event_title', $post['event_title']);
               $statement->bindValue(':start_date', $post['start_date']);
               $statement->bindValue(':end_date', $post['end_date']);
               $statement->bindValue(':image', $post['image']);
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':fb_url', $post['fb_url']);
               $statement->bindValue(':created_by', $user_id);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $this->dbh->lastInsertId();
               }

               throw new Exception('Could not add event');
          }

          public function edit($post) {
               $sql = 'UPDATE sites_agendas_events SET event_title = :event_title, start_date = :start_date, end_date = :end_date, image = :image, content = :content, fb_url = :fb_url, modified = :modified WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':event_title', $post['event_title']);
               $statement->bindValue(':start_date', $post['start_date']);
               $statement->bindValue(':end_date', $post['end_date']);
               $statement->bindValue(':image', $post['image']);
               $statement->bindValue(':content', $post['content']);
               $statement->bindValue(':fb_url', $post['fb_url']);
               $statement->bindValue(':modified', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not edit event');
          }

          public function delete($id) {
               $sql = 'DELETE FROM sites_agendas_events WHERE id = :id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':id', $id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete events');
          }

          public function deleteForAgenda($agenda_id) {
               $sql = 'DELETE FROM sites_agendas_events WHERE agenda_id = :agenda_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':agenda_id', $agenda_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete events for agenda');
          }

          public function deleteForSite($site_id) {
               $sql = 'DELETE FROM sites_agendas_events WHERE site_id = :site_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':site_id', $site_id);

               if($statement->execute()) {
                    return true;
               }

               throw new Exception('Could not delete events for site');
          }

      }
