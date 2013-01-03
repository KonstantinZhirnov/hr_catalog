<?php

/**
 * Class for work with users who have access to the system
 *
 * @author Konstantin Zhirnov
 */
class User {
  /**
   * id of current user
   * @var int 
   */
  private $id = null;
  
  /**
   * current user role
   * @var string 
   */
  private $role = null;
  
  /**
   * current user login
   * @var string
   */
  private $login = null;
  
  /**
   * UNIX timestimp of last user activity
   * @var int 
   */
  private $lastLoginTime = null;
  
  /**
   * hash of user password 
   * @var string 
   */
  private $password = null;
  
  /**
   * load user from database if exists otherwise create new instance of user
   * @param string user login 
   * @param string hache of user password 
   */
  public function __construct($login, $password) {
    $this->login = $login;
    $this->password = $password;
    
    $dbResult = System::database()->query('select u.*
      from :table_users u 
      where u.`login` = :login 
        and u.`password` = :password');
    
    $dbResult->bindTable(':table_users', TABLE_USERS);
    $dbResult->bindValue(':login', $this->login);
    $dbResult->bindValue(':password', $this->password);
    
    $dbResult->execute();
    if(System::database()->isError()) {
      //TODO: add logging here
    }
    
    if($dbResult->numberOfRows() > 0) {
      $roles = UserRoles::getInstance();
      $this->id = $dbResult->valueInt('id');
      $this->role = $roles->getRole($dbResult->valueInt('role_id'));
      $this->lastLoginTime = $dbResult->valueInt('last_login');
    }
  }
  
  /**
   * Save current user/changes to database
   */
  public function saveToDatabase() {
    if($this->id != 0) {
      $dbResult = System::database()->query('update :table_users set `role_id` = :role_id, `login` = :login, `password` = :password
        where `id` = :id');
      
      $dbResult->bindInt(':rid', $this->id);
    } else {
      $dbResult = System::database()->query('insert into :table_users (`role_id`, `login`, `password`)
        values (:role_id, :login, :password);');
    }
    $dbResult->bindTable(':table_users', TABLE_USERS);
    $dbResult->bindInt(':role_id', $this->role['id']);
    $dbResult->bindValue(':login', $this->login);
    $dbResult->bindValue(':password', $this->password);

    $dbResult->execute();
  }
}

?>
