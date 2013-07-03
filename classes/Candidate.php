<?php

/**
 * Interaction with candites table in database
 *
 * @author Konstantin Zhirnov
 */
class Candidate extends DatabaseInteraction {
  protected $id;
  protected $city_id;
  protected $vacancy_id;
  protected $qualification_id;
  protected $status_id;
  protected $manager_id;
  protected $name;
  protected $lastname;
  protected $patronymic;
  protected $portfolio;
  protected $rezume;
  protected $pay;
  protected $is_remote;
  protected $comment;
 
  public function __construct() {
    $this->database = System::database();
  }
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  public  function fillFromArray($data) {
    Log::Show($data);
    if($data){
      if(isset($data['id'])) {
        $this->id = $data['id'];
      }
      $this->activity['id'] = $data['activity_id'];
      $this->name = $data['name'];
      $this->lastname = $data['lastname'];
      $this->patronymic = $data['patronymic'];
      $this->portfolio = $data['portfolio'];
      $this->rezume = $data['rezume'];
      $this->pay = $data['pay'];
      $this->is_remote = $data['name'];
      $this->name = $data['name'];
      $this->name = $data['is_remote'];
      $this->comment = $data['comment'];
    }
  }
  
  public function getById() {
    
  }
  
  public static function getFromDB($conditions = null) {
    self::$_dbResult = System::database()->query("select * from :candidates_table");
    self::$_dbResult->bindTable(":candidates_table", TABLE_CANDIDATES);
    
    if($conditions && is_array($conditions)) {
      $this->dbResult->appendQuery(" where");
      
      foreach($conditions as $key => $value) {
        self::$_dbResult->appendQuery(" `{$key}` = :{$key}");
        self::$_dbResult->bindValue(":{$key}", $value);
      }
    }
    
    self::$_dbResult->execute();
    
    $result = array();
    while(self::$_dbResult->next()) {
      $result[] = self::$_dbResult->toArray();
    }
    return $result;
  }
}

?>
