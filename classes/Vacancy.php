<?php

/**
 * Interaction with table vacancies database
 *
 * @author Konstantin Zhirnov
 */
class Vacancy extends DatabaseInteraction {
  
  const ACTIVE = '1';
  const CLOSE = '2';
  const RESERVE = '3';
  
  protected $id;
  
  public $activityId;
  
  public $name;
  
  public function __construct() {
    parent::__construct();
  }
  
  /**
   * get instance of Vacancy from database by id
   * @param int $id
   * @return Vacancy instance of Vacancy
   */
  public static function getById($id){
    $vacancy = new Vacancy();
    $vacancy->getFromDB(array('id' => $id));
    
    if($vacancy->_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple vacancies found');
    }
    
    $vacancy->fillFromArray($vacancy->_dbResult->toArray());
    
    return $vacancy;
  }
  
  /**
   * get instance of Vacancy from database
   * @param array $conditions conditions for retrieve database record.
   * @throws Exception If multiple record finded throw exception
   */
  protected function getFromDB($conditions) {
    $this->_dbResult = $this->_database->query("select * from :vacancy_table");
    $this->_dbResult->bindTable(":vacancy_table", TABLE_VACANCIES);
    if($conditions && is_array($conditions)) {
      $this->_dbResult->appendQuery(" where");
      Helper::addSqlConditions($this->_dbResult, $conditions);
    }
    $this->_dbResult->execute();
  }
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  protected  function fillFromArray($data) {
    if($data){
      $this->id = $data['id'];
      $this->activityId = $data['activity_id'];
      $this->name = $data['name'];
    }
  }
  
  /**
   * Save changes to database or create new record if vacancy id is empty
   */
  public function Save () {
    $conditions = array('id' => $this->id, 'activity_id' => $this->activityId, 'name' => $this->name);
    $this->_dbResult = $this->_database->query("insert into :vacancy_table set ");
    $this->_dbResult->bindTable(":vacancy_table", TABLE_VACANCIES);
    Helper::addSqlConditions($this->_dbResult, $conditions);
    $this->_dbResult->appendQuery(" on duplicate key update ");
    Helper::addSqlConditions($this->_dbResult, $conditions);
    $this->_dbResult->execute();
  }
  
  /**
   * Set vacancy closed (savsed all changes maded before)
   */
  public function Close() {
    $this->activityId = Vacancy::CLOSE;
    $this->Save();
  }
  
  /**
   * Set vacancy reserved (savsed all changes maded before)
   */
  public function setReserve() {
    $this->activityId = Vacancy::RESERVE;
    $this->Save();
  }
  
  /**
   * retrieve vacancies from database by specified conditions
   * @param array $conditions search conditions
   * @return array array of finded vacancies
   */
  public static function getVacancies($conditions = null) {
    $result = array();
    
    $dbResult = System::database()->query('select * from :vacancy_table');
    $dbResult->bindTable(":vacancy_table", TABLE_VACANCIES);
    if($conditions) {
      $dbResult->appendQuery(' where');
      Helper::addSqlConditions($dbResult, $conditions);
    }
    
    $dbResult->execute();
    
    while($dbResult->Next()) {
      $vacancy = new Vacancy();
      $vacancy->fillFromArray($dbResult->toArray());
      $result[] = $vacancy;
    }
    
    return $result;
  }
}

?>