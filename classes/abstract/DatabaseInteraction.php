<?php

/**
 * Interaction with database
 *
 * @author Konstantin Zhirnov
 */
abstract class DatabaseInteraction {
  /**
   * 
   * @var iDatabase_mysqli
   */
  protected $_database = null;
  
  /**
   * 
   * @var iDatabase_Result
   */
  protected $_dbResult = null;
  
  public function __construct() {
     $this->_database = System::database();
  }
  
  protected abstract function getFromDB($conditions);
  
  protected abstract function fillFromArray($data);
  
  public function __get($name) {
    if(!Helper::startsWith($name, "_")) {
      return $this->$name;
    }
    return null;
  }
}

?>
