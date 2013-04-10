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
  
  protected $activity;
  
  protected $name;
  
  protected static $_databaseTable = TABLE_VACANCIES;

  /**
   * get instance of Vacancy from database by id
   * @param int $id
   * @return Vacancy instance of Vacancy
   */
  public static function getById($id){
    static::getFromDB(array('v.id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple vacancies found');
    }
    $vacancy = new Vacancy();
    $vacancy->fillFromArray(static::$_dbResult->toArray());
    return $vacancy;
  }
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  public  function fillFromArray($data) {
    if($data){
      if(isset($data['id'])) {
        $this->id = $data['id'];
      }
      $this->activity['id'] = $data['activity_id'];
      $this->name = $data['name'];
      if(isset($data['activity_name'])) {
        $this->activity['name'] = $data['activity_name'];
      }
    }
  }
  
  /**
   * Save changes to database or create new record if vacancy id is empty
   */
  public function Save () {
    $conditions = array('id' => $this->id, 'activity_id' => $this->activity['id'], 'name' => $this->name);
    parent::SaveToDatabase($conditions);
    if (!$this->id) {
      $this->id = self::$_database->getLastInsertId();
    }
  }
  
  /**
   * Set vacancy closed (savsed all changes maded before)
   */
  public function SetClosed() {
    $this->activityId = Vacancy::CLOSE;
    $this->Save();
  }
  
  /**
   * Set vacancy reserved (savsed all changes maded before)
   */
  public function setReserved() {
    $this->activityId = Vacancy::RESERVE;
    $this->Save();
  }
  
  public static function getFromDB($conditions = null) {
    self::$_dbResult = System::database()->query('select v.*, va.`name` activity_name from :db_table v
      inner join :table_activities va on v.`activity_id` = va.`id` ');
    self::$_dbResult->bindTable(":db_table", static::$_databaseTable);
    self::$_dbResult->bindTable(":table_activities", TABLE_VACANCY_ACTIVITIES);
    if($conditions && is_array($conditions)) {
      self::$_dbResult->appendQuery(" where");
      Helper::addSqlConditions(self::$_dbResult, $conditions);
    }
    self::$_dbResult->execute();
  }
}

?>