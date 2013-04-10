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
  protected static function getFromDB($conditions) {
    if(!static::$_database) {
      static::$_database = System::database();
    }
    
    static::$_dbResult = static::$_database->query("select * from :db_table");
    static::$_dbResult->bindTable(":db_table", static::$_dabaseTable);
    if($conditions && is_array($conditions)) {
      static::$_dbResult->appendQuery(" where");
      Helper::addSqlConditions(static::$_dbResult, $conditions);
    }
    static::$_dbResult->execute();
  }
  
  public abstract function fillFromArray($data);
  
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
    self::$_dbResult = self::$_database->query("insert into :db_table set ");
    self::$_dbResult->bindTable(":db_table", static::$_databaseTable);
    Helper::addSqlConditions(self::$_dbResult, $conditions);
    self::$_dbResult->appendQuery(" on duplicate key update ");
    Helper::addSqlConditions(self::$_dbResult, $conditions);
    self::$_dbResult->execute();
  }
  
  /**
   * retrieve qualifications from database by specified conditions
   * @param array $conditions search conditions
   * @return array array of finded qualifications
   */
  public static function getItems($conditions = null) {
    static::getFromDB($conditions);
    
    while(self::$_dbResult->Next()) {
      $className = get_called_class();
      $instance = new $className();
      $data = self::$_dbResult->toArray();
      $instance->fillFromArray($data);
      $result[$instance->id] = $instance;
    }
    
    return $result;
  }
}

?>
