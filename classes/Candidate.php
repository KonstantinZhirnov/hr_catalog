<?php

/**
 * Interaction with candites table in database
 *
 * @author Konstantin Zhirnov
 */
class Candidate {
  /**
   *
   * @var iDatabase_mysqli
   */
  private $database = null;
  
  /**
   *
   * @var iDatabase_Result
   */
  private $dbResult = null;
  
  public function __construct() {
    $this->database = System::database();
  }
  
  public function getById() {
    
  }
  
  private function getFromDb($conditions) {
    $this->dbResult = $this->database->query("select * from :candidates_table");
    $this->dbResult->bindTable(":candidates_table", TABLE_CANDIDATES);
    
    if($conditions && is_array($conditions)) {
      $this->dbResult->appendQuery(" where");
      
      foreach($conditions as $key => $value) {
        $this->dbResult->appendQuery(" `{$key}` = :{$key}");
        $this->dbResult->bindValue(":{$key}", $value);
      }
    }
    
    $this->dbResult->execute();
    
    $result = array();
    while($this->dbResult->next()) {
      $result[] = $this->dbResult->toArray();
    }
    return $result;
  }
}

?>
