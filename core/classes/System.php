<?php
/**
 * class for store global variables
 *
 * @author Konstantin Zhirnov
 */
class System implements ISingleton{
  
  private static $variables = array();
  private static $instance = null;
  
  public static $basePath = '';
  
  public static function getInstance($param = false) {
    if(self::$instance == null) {
      self::$instance = new System();
    }
    return self::$instance;
  }

  private function __construct() {
    self::$basePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR;
  }

  public function __get($name){
    return self::$name();
  }
  
  public function __set($name, $value) {
    self::$name($value);
  }
  
  public function __callStatic($name, $arguments = null) {
    return self::addPropertie($name, $arguments[0]);
  }
  
  public function __call($name, $arguments = null) {
    return self::addPropertie($name, $arguments[0]);
  }
  
  private static function addPropertie($name, $value = null) {
    if($value) {
      self::$variables[$name] = $value;
    } else {
      if(isset(self::$variables[$name])) {
        return self::$variables[$name];
      }

      return null;
    }
  }
}

System::getInstance();

?>
