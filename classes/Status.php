<?php
/**
 * work with emploee/candidate status
 *
 * @author Konstantin Zhirnov
 */
class Status extends DatabaseInteraction {
  protected $id = 0;
  protected $name = 'test';
  
  protected static $_databaseTable = TABLE_EMPLOEE_STATUSES;
  
  public function fillFromArray($data) {
    if(!is_array($data) || !$data) {
        return;
    }
    $this->id = $data['id'];
    $this->name = $data['name'];
  }
  
  /**
   * Save changes to database or create new record if Status id is empty
   */
  public function Save () {
    $conditions = array('id' => $this->id, 'name' => $this->name);
    parent::SaveToDatabase($conditions);
  }
  
  /**
   * get instance of Qualification from database by id
   * @param int $id
   * @return Qualification instance of Qualification
   */
  public static function getById($id){
    $instance = new Status();
    $instance->getFromDB(array('id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple statuses found');
    }
    
    $instance->fillFromArray(static::$_dbResult->toArray());
    
    return $instance;
  }
}

?>
