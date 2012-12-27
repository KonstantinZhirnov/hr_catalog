<?php
/**
 * load data from config file to global variables
 *
 * @author Konstantin Zhirnov
 */
class ConfigModule implements IModule {
  
  private $path = 'config/config.php';
  
  public function Run() {
    if(file_exists($this->path)) {
      require_once $this->path;
    }
    foreach($config as $item => $value){
      System::$item($value);
    }
  }
  
  public function __construct($path = null) {
    if($path) {
      $this->path = $path;
    }
  }
}

?>
