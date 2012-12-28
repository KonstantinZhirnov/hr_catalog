<?php
/**
 * system configugation actions
 *
 * @author keen_lad
 */
class Config implements ISingletone {
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
    Log::Show(System::$basePath);
    Log::Show($filePath);
    Log::Show(file_exists($filePath), true);
    
    if(file_exists($filePath)) {
      require_once $filePath;
    }
    
    Log::Show($config);
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
