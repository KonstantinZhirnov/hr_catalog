<?php

/**
 * Description of Qualification
 *
 * @author Konstantin Zhirnov
 */
class Qualification extends DatabaseInteraction {
  
  protected $id;
  public $name;
  
  protected static $_databaseTable = TABLE_QUALIFICATIONS;
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  public function fillFromArray($data) {
    if($data){
      $this->id = $data['id'];
      $this->name = $data['name'];
    }
  }
  
  /**
   * get instance of Qualification from database by id
   * @param int $id
   * @return Qualification instance of Qualification
   */
  public static function getById($id){
    $instance = new Qualification();
    $instance->getFromDB(array('id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple vacancies found');
    }
    
    $instance->fillFromArray(static::$_dbResult->toArray());
    
    return $instance;
  }
  
  /**
   * Save changes to database or create new record if vacancy id is empty
   */
  public function Save () {
    $conditions = array('id' => $this->id, 'name' => $this->name);
    parent::SaveToDatabase($conditions);
  }
}

?>
