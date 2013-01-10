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
  
  public static function __callStatic($name, $arguments = null) {
    return self::addPropertie($name, $arguments?$arguments[0]:null);
  }
  
  public function __call($name, $arguments = null) {
    return self::addPropertie($name, $arguments?$arguments[0]:null);
  }
  
  private static function addPropertie($name, $value = null) {
    if($value) {
      self::$variables[$name] = $value;
    } else {
      if(isset(self::$variables[$name])) {
        return self::$variables[$name];
      } elseif (class_exists($name) && array_key_exists('ISingleton', class_implements($name))) {
        return $name::getInstance();
      }

      return null;
    }
  }
}

System::getInstance();

?>
