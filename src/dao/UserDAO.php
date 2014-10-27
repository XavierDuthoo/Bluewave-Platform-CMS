<?php
require_once WWW_ROOT . 'Classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

class UserDAO {
     public $dbh;

     public function __construct() {
          $this->dbh = DatabasePDO::getInstance();
     }

     public function createUser($post) {
          $sql = "INSERT INTO users(firstname, lastname, email, language, company, type, activated, banned, created, modified) VALUES(:firstname, :lastname, :email, :language, :company, :type, :activated, :banned, :created, :modified)";
          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':firstname', $post['firstname']);
          $statement->bindValue(':lastname', $post['lastname']);
          $statement->bindValue(':email', strtolower($post['email']));
          $statement->bindValue(':language', $post['language']);
          $statement->bindValue(':company', $post['company']);
          $statement->bindValue(':activated', 0);
          $statement->bindValue(':banned', 0);
          $statement->bindValue(':type', $post['type']);
          $statement->bindValue(':created', date('Y-m-d H:i:s'));
          $statement->bindValue(':modified', date('Y-m-d H:i:s'));


          if($statement->execute()) {
               return $this->dbh->lastInsertId();
          }

          throw new Exception('Kon geen user bijmaken');
     }

     public function login($post) {
          $sql = "SELECT * FROM users WHERE email = :email AND password = :password AND activated = 1 AND banned = 0";
          $statement = $this->dbh->prepare($sql);
          $statement->setFetchMode(PDO::FETCH_ASSOC);
          $statement->bindValue(":email", $post["email"]);
          $statement->bindValue(":password", sha1($post["password"] . Config::SALT));
          if($statement->execute()) {
               $user = $statement->fetch();
               if($statement->rowCount() > 0) {
                    return $user;
               } else {
                    return -1;
               }
          }
          throw new Exception("We kunnen je momenteel niet inloggen");

     }

     public function removeUserWithID($userID) {
          $sql = "DELETE FROM users WHERE id = :id";
          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':id', $userID);

          if($statement->execute()) {
               return true;
          }

          throw new Exception("Kon de user niet verwijderen");
     }

     public function getAllUsers() {
          $sql = "SELECT * FROM users";
          $statement = $this->dbh->prepare($sql);

          if($statement->execute()) {
               $users = $statement->fetchAll(PDO::FETCH_ASSOC);
               return $users;
          }

          throw new Exception("Kon de users niet ophalen uit de database");
     }

     public function getUserByID($userID) {
          $sql = "SELECT * FROM users WHERE id = :id";
          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':id', $userID);

          if($statement->execute()) {
               $user = $statement->fetch(PDO::FETCH_ASSOC);
               return $user;
          }

          throw new Exception("Kon de gebruiker voor dit ID niet ophalen");
     }

     public function updatePassword($password, $user_id) {
          $sql = "UPDATE users SET password = :password WHERE id = :id";
          $statement = $this->dbh->prepare($sql);
          $statement->setFetchMode(PDO::FETCH_ASSOC);
          $statement->bindValue(":password", sha1($password . Config::SALT));
          $statement->bindValue(":id", $user_id);
          if($statement->execute()) {
               return true;
          }

          throw new Exception("We konden je wachtwoord niet updaten");
     }

     public function updateUserWithNewPassword($post) {
          $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, company = :company, language = :language, password = :password, type = :type, banned = :banned, modified = :modified WHERE id = :id";
          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':firstname', $post["firstname"]);
          $statement->bindValue(':lastname', $post["lastname"]);
          $statement->bindValue(':email', $post["email"]);
          $statement->bindValue(':password', sha1($post["password"] . Config::SALT));
          $statement->bindValue(':company', $post["company"]);
          $statement->bindValue(':language', $post["language"]);
          $statement->bindValue(':type', $post["type"]);
          $statement->bindValue(':banned', $post["banned"]);
          $statement->bindValue(':modified', date('Y-m-d H:i:s'));
          $statement->bindValue(':id', $post["id"]);

          if($statement->execute()) {
               return true;
          }

          throw new Exception('Kon de gebruiker niet updaten met een nieuw wachtwoord');
     }

     public function updateUserWithoutNewPassword($post) {
          $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, company = :company, language = :language, type = :type, banned = :banned, modified = :modified WHERE id = :id";
          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':firstname', $post["firstname"]);
          $statement->bindValue(':lastname', $post["lastname"]);
          $statement->bindValue(':email', $post["email"]);
          $statement->bindValue(':company', $post["company"]);
          $statement->bindValue(':language', $post["language"]);
          $statement->bindValue(':type', $post["type"]);
          $statement->bindValue(':banned', $post["banned"]);
          $statement->bindValue(':modified', date('Y-m-d H:i:s'));
          $statement->bindValue(':id', $post["id"]);

          if($statement->execute()) {
               return true;
          }

          throw new Exception('Kon de gebruiker niet updaten zonder nieuw wachtwoord');
     }

          # Check if user already exists in DB as email is a unique field
          # Returns true if already exists, returns false if not
     public function alreadyRegistered($email) {
          $sql = "SELECT * FROM users WHERE email = :email";

          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':email', $email);

          if($statement->execute()) {
               $matches = $statement->fetchAll(PDO::FETCH_ASSOC);
               if(count($matches) == 0) {
                    return false;
               }
          }

          return true;
     }

          # Simple function that updates the last login time on the server
          # Returns true when execution was succesful
     public function updateLastLogin($user_id) {
          $sql = 'UPDATE users SET last_login = :now WHERE id = :id';

          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':now', date('Y-m-d H:i:s'));
          $statement->bindValue(':id', $user_id);

          if($statement->execute()) {
               return true;
          }

          return false;
     }

          # Updates image field in db
          # Returns true if successful
     public function updateImageForUser($user_id, $image_name) {
          $sql = 'UPDATE users SET image = :image WHERE id = :id';

          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':image', $image_name);
          $statement->bindValue(':id', $user_id);

          if($statement->execute()) {
               return true;
          }

          return false;
     }

          # Activates a user in db so he/she can login
          # Returns true if successful
     public function activateUser($user_id) {
          $sql = 'UPDATE users SET activated = 1 WHERE id = :id';

          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':id', $user_id);

          if($statement->execute()) {
               return true;
          }

          return false;
     }

          # Find a user by email address
          # Returns the user if found
     public function findUserByEmail($email) {
          $sql = 'SELECT * FROM users WHERE email = :email';

          $statement = $this->dbh->prepare($sql);
          $statement->bindValue(':email', $email);
          $statement->setFetchMode(PDO::FETCH_ASSOC);

          if($statement->execute()) {
               $user = $statement->fetch();
               return $user;
          }

          return false;
     }

}
