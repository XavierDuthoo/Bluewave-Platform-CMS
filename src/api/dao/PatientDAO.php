<?php
require_once WWW_ROOT . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DatabasePDO.php';

class PatientDAO {
    public $dbh;

    public function __construct() {
        $this->dbh = DatabasePDO::getInstance();
    }

    public function getCompletedPolls($patientID) {
        $sql = 'SELECT test_index FROM test_results WHERE patient_number = :patient_id GROUP BY test_index';
        $statement = $this->dbh->prepare($sql);

        $statement->bindValue(':patient_id', $patientID);


        if($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}