<?php

/**
 * Actions with user roles existing in database
 *
 * @author Konstantin Zhirnov
 */
class UserRoles implements ISingleton {
  private static $instance = null;
  
  private $roles = array();
  
  /**
   * 
   * @param type $param
   * @return UserRoles
   */
  public static function getInstance($param = null) {
    if(self::$instance == null) {
      self::$instance = new UserRoles();
    }
    
    return self::$instance;
  }
  
  protected function __construct() {
    $dbResult = System::database()->query('select * from :table_roles');
     $dbResult->bindTable(':table_roles', TABLE_USER_ROLES);
    
    $dbResult->execute();
    while($dbResult->Next()) {
      $this->roles[$dbResult->valueInt('id')] = $dbResult->value('name');
    }
  }
  
  /**
   * Retrieve role by Id
   * @param int id of needed role
   * @return if role exist - array(id, name) else null
   */
  public function getRole($id) {
    if(isset($this->roles[$id])) {
      return array('id' => $id, 'name' => $this->roles[$id]);
    }
    
    return null;
  }
}

?>
