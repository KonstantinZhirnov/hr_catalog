<?php

/**
 * Description of City
 *
 * @author Konstantin Zhirnov
 */
class City extends DatabaseInteraction implements ISingleton {
  /**
   * @var int sity id
   */
  protected $id;
  /**
   * @var string city name
   */
  protected $name;
  /**
   * @var Region City region
   */
  protected $region;
  
  /**
   * @var array list of existing cities 
   */
  private static $_cities = null;
  
  public static function getInstance($param = null) {
    if(self::$_cities == null) {
      self::$_cities = static::getItems();
    }
    return $param ? self::$_cities[$param] : self::$_cities;
  }
  
  protected static $_databaseTable = TABLE_CITIES;
  
  /**
   * sets internal variables by data retrieved from database
   * @param array $data array of data retrieved from database
   */
  protected function fillFromArray($data) {
    if($data){
      $this->id = $data['id'];
      $this->name = $data['name'];
      $this->region = Region::getInstance($data['region_id']);
    }
  }
  
  /**
   * get instance of Qualification from database by id
   * @param int $id
   * @return Qualification instance of Qualification
   */
  public static function getById($id){
    $instance = new City();
    $instance->getFromDB(array('id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple citities found');
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
  
  /**
   * retrieve cities from database by specified conditions
   * @param array $conditions search conditions
   * @return array array of finded cities
   */
  public static function getItems($conditions = null) {
    $result = array();
    
    $dbResult = System::database()->query('select c.*, r.name regionName from :table_cities c
      inner join :table_regions r on c.`region_id` = r.`id` ');
    $dbResult->bindTable(":table_cities", static::$_databaseTable);
    $dbResult->bindTable(":table_regions", TABLE_REGIONS);
    
    if(!isset($conditions['is_active'])){
      $conditions['is_active'] = 1;
    }
    
    if($conditions) {
      $dbResult->appendQuery(' where');
      Helper::addSqlConditions($dbResult, $conditions);
    }
    $dbResult->appendQuery(" order by r.name, c.name");
    
    $dbResult->execute();
    
    while($dbResult->Next()) {
      $city = new City();
      $city->fillFromArray($dbResult->toArray());
      $result[$city->region->id][$city->id] = $city;
    }
    
    return $result;
  }
}

?>
