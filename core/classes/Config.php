<?php
/**
 * system configugation actions
 *
 * @author keen_lad
 */
class Config implements ISingleton {
  private static $configItems = array();
  private static $instance = null;
  
  public static function getInstance($param = null) {
    
    if(self::$instance === null) {
      self::$instance = new Config($param);
    }
    
    return self::$instance;
  }


  private function __construct($path = null) {
    $filePath = $path ? System::$basePath . $path : System::$basePath . 'config/config.php';
    
    if(file_exists($filePath)) {
      require_once $filePath;
    }

    foreach ($config as $item => $value) {
      $this->$item = $value;
    }
  }
  
  public function __get($name) {
      return self::$configItems[$name];
  }
  
  protected function __set($name, $value) {
    self::$configItems[$name] = $value;
  }
}

?>
