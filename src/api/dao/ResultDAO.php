<?php
require_once WWW_ROOT . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

class ResultDAO
{
    public $dbh;

    public function __construct() {
        $this->dbh = DatabasePDO::getInstance();
    }

    // public function addUser($data) {
    //     $sql = 'INSERT INTO usersnl(name, firstname, email, code, schifting, antwoord, geboorte) VALUES(:name, :firstname, :email, :code, :schifting, :antwoord, :geboorte)';
    //     if($data['language'] == "fr") {
    //         $sql = 'INSERT INTO usersfr(name, firstname, email, code, schifting, antwoord, geboorte) VALUES(:name, :firstname, :email, :code, :schifting, :antwoord, :geboorte)';
    //     }
    //     $statement = $this->dbh->prepare($sql);

    //     // $statement->bindValue(':table', 'users' . $data['language']);
    //     $statement->bindValue(':name',$data['name']);
    //     $statement->bindValue(':firstname',$data['firstname']);
    //     $statement->bindValue(':email', $data['email']);
    //     $statement->bindValue(':code', $data['code']);
    //     $statement->bindValue(':schifting', $data['downloads']);
    //     $statement->bindValue(':antwoord', $data['answer']);
    //     $statement->bindValue(':geboorte', date('Y-m-d', strtotime($data['dob'])));

    //     if($statement->execute()) {
    //         $id = $this->dbh->lastInsertId();
    //         return $id;
    //     }
    // }

    public function addResult($data) {
        $sql = 'INSERT INTO test_results(patient_number, test_index, answers, added) VALUES(:patient_number, :test_index, :answers, :added)';
        $statement = $this->dbh->prepare($sql);

        $statement->bindValue(':patient_number', $data['patientNumber']);
        $statement->bindValue(':test_index', $data['currentTestIndex']);
        $statement->bindValue(':answers', json_encode($data['answers']));
        $statement->bindValue(':added', date('Y-m-d H:i:s'));

        if($statement->execute()) {
            $id = $this->dbh->lastInsertId();
            return $id;
        }
    }
}