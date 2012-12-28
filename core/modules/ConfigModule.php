<?php
/**
 * load data from config file to global variables
 *
 * @author Konstantin Zhirnov
 */
class ConfigModule implements IModule {
  
  private $path = 'config/config.php';
  
  public function Run() {
    System::Config(Config::getInstance($this->path));
  }
  
  public function __construct($path = null) {
    if($path) {
      $this->path = $path;
    }
  }
}

?>
