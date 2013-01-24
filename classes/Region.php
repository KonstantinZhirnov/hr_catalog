<?php

/**
 * Description of Region
 *
 * @author Konstantin Zhirnov
 */
class Region extends DatabaseInteraction implements ISingleton {
  private static $_regions = null;
  
  protected $id;
  protected $name;
  
  protected static $_databaseTable = TABLE_REGIONS;
  
  public static function getInstance($param = null) {
    if(self::$_regions == null){
      self::$_regions = static::getItems();
    }
    
    return $param ? self::$_regions[$param] : self::$_regions;
  }
  
  public function fillFromArray($data) {
    $this->id = $data['id'];
    $this->name = $data['name'];
  }
}

?>
