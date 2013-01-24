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
  
  protected static $_databaseTable = TABLE_VACANCIES;

  /**
   * get instance of Vacancy from database by id
   * @param int $id
   * @return Vacancy instance of Vacancy
   */
  public static function getById($id){
    $vacancy = new Vacancy();
    $vacancy->getFromDB(array('id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple vacancies found');
    }
    
    $vacancy->fillFromArray(static::$_dbResult->toArray());
    
    return $vacancy;
  }
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  protected  function fillFromArray($data) {
    if($data){
      $this->id = $data['id'];
      //TODO: в будущем сделать объект активности
      $this->activityId = $data['activity_id'];
      $this->name = $data['name'];
    }
  }
  
  /**
   * Save changes to database or create new record if vacancy id is empty
   */
  public function Save () {
    $conditions = array('id' => $this->id, 'activity_id' => $this->activityId, 'name' => $this->name);
    parent::SaveToDatabase($conditions);
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
}

?>