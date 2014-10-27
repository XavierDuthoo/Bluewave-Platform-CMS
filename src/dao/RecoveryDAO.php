<?php
     require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

     class RecoveryDAO {
          public $dbh;

          public function __construct() {
               $this->dbh = DatabasePDO::getInstance();
          }

          # Creates a key for account recovery or account activation
          # Returns the key to send in the email
          public function createActivationRecoveryKey($user_id) {
               $sql = 'INSERT INTO recovery(user_id, rkey, valid, created) VALUES(:user_id, :rkey, :valid, :created)';

               $rkey = sha1($user_id . Config::SALT . strtotime(date('Y:m:d H:i:s')));

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':user_id', $user_id);
               $statement->bindValue(':rkey', $rkey);
               $statement->bindValue(':valid', 1);
               $statement->bindValue(':created', date('Y-m-d H:i:s'));

               if($statement->execute()) {
                    return $rkey;
               }

               throw new Exception('Could not create recovery key');
          }

          # Checks if a key exists in the database and if it is still valid
          # Returns the record found in the db if found
          public function checkIfRecoveryKeyIsStillValid($key) {
               $sql = 'SELECT * FROM recovery WHERE rkey = :rkey AND valid = 1';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':rkey', $key);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    $key_information = $statement->fetch();
                    return $key_information;
               }
          }

          # Sets valid = 0 in the db when a key was used
          # Returns the user id if successful
          public function invalidateUsedKey($key) {
               $sql = 'UPDATE recovery SET valid = 0 WHERE rkey = :rkey';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':rkey', $key);

               if($statement->execute()) {
                    return $this->getUserIDForKey($key);
               }
          }

          # Gets a user id for a key, doesn't matter if still valid or not
          # Returns the user id if successful
          public function getUserIDForKey($key) {
               $sql = 'SELECT * FROM recovery WHERE rkey = :rkey';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':rkey', $key);
               $statement->setFetchMode(PDO::FETCH_ASSOC);

               if($statement->execute()) {
                    $key_information = $statement->fetch();
                    return $key_information['user_id'];
               }
          }

          public function setAllLinksInvalidForUserID($user_id) {
               $sql = 'UPDATE recovery SET valid = 0 WHERE user_id = :user_id';

               $statement = $this->dbh->prepare($sql);
               $statement->bindValue(':user_id', $user_id);

               if($statement->execute()) {
                    return true;
               }

               return false;
          }

     }
