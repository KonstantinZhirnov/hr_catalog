<?php

/**
 * Interaction with database
 *
 * @author Konstantin Zhirnov
 */
abstract class DatabaseInteraction {
  /**
   * @var iDatabase_mysqli
   */
  protected static $_database = null;
  
  /**
   * @var iDatabase_Result
   */
  protected static $_dbResult = null;
  
  /**
   * name of database table for object interaction
   * @var type 
   */
  protected static $_dabaseTable = '';


  public function __construct() {
     static::$_database = System::database();
  }
  
  /**
   * get instance of object from database
   * @param array $conditions conditions for retrieve database record.
   */
  protected function getFromDB($conditions) {
    static::$_dbResult = static::$_database->query("select * from :db_table");
    static::$_dbResult->bindTable(":db_table", static::$_dabaseTable);
    if($conditions && is_array($conditions)) {
      static::$_dbResult->appendQuery(" where");
      Helper::addSqlConditions(static::$_dbResult, $conditions);
    }
    static::$_dbResult->execute();
  }
  
  protected abstract function fillFromArray($data);
  
  public function __get($name) {
    if(!Helper::startsWith($name, "_")) {
      return $this->$name;
    }
    return null;
  }
  
  /**
   * Save data to database or update existed record
   * @param array $conditions conditions which will be saved
   */
  protected function SaveToDatabase($conditions) {
    static::$_dbResult = static::$_database->query("insert into :vacancy_table set ");
    static::$_dbResult->bindTable(":vacancy_table", static::$_databaseTable);
    Helper::addSqlConditions(static::$_dbResult, $conditions);
    static::$_dbResult->appendQuery(" on duplicate key update ");
    Helper::addSqlConditions(static::$_dbResult, $conditions);
    static::$_dbResult->execute();
  }
  
  /**
   * retrieve qualifications from database by specified conditions
   * @param array $conditions search conditions
   * @return array array of finded qualifications
   */
  public static function getItems($conditions = null) {
    $result = array();
    
    $dbResult = System::database()->query('select * from :db_table');
    $dbResult->bindTable(":db_table", static::$_databaseTable);
    
    if($conditions) {
      $dbResult->appendQuery(' where');
      Helper::addSqlConditions($dbResult, $conditions);
    }
    
    $dbResult->execute();
    
    while($dbResult->Next()) {
      $className = get_called_class();
      $instance = new $className();
      $data = $dbResult->toArray();
      $instance->fillFromArray($data);
      $result[$instance->id] = $instance;
    }
    
    return $result;
  }
}

?>
