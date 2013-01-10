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
   * Key for identity user
   * @var string
   */
  private $authKey = null;
  
  /**
   * UNIX timestamp for define when auth is expired
   * @var int
   */
  private $authExpired = null;
  
  /**
   * load user from database if exists otherwise create new instance of user
   * @param string $login user login 
   * @param string $password hache of user password 
   */
  public function __construct($login, $password) {
    $this->login = $login;
    $this->password = $password;
    
    $userInfo = self::getUserFromDb($login, $password);
    
    if($userInfo) {
      $this->id = $userInfo['id'];
      $this->role = $userInfo['role'];
      $this->lastLoginTime = $userInfo['last_login'];
      $this->authKey = $userInfo['auth_key'];
      $this->authExpired = $userInfo['auth_expire'];
    }
  }
  
  /**
   * Save current user/changes to database
   */
  public function saveToDatabase() {
    if($this->id != 0) {
      $dbResult = System::database()->query('update :table_users set `role_id` = :role_id, `login` = :login, `password` = :password, `auth_key` = :auth_key, `auth_expire` = :auth_expire
        where `id` = :id');
      
      $dbResult->bindInt(':id', $this->id);
      $dbResult->bindValue(':auth_key', $this->authKey);
      $dbResult->bindInt(':auth_expire', $this->authExpired);
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
  
  /**
   * retrieve instance of user from database
   * @param string $login user login
   * @param string $password user password
   * @param string $authKey user auth key for check is user valid
   * @return array contains info about finded user or null
   */
  private static function getUserFromDb($login, $password, $authKey = null) {
    $result = null;
    
    $dbResult = System::database()->query('select u.*
      from :table_users u 
      where u.`login` = :login 
        and u.`password` = :password');
    
    if($authKey) {
      $dbResult->appendQuery('and u.`auth_key` = :auth_key
          and u.`auth_expire` >= UNIX_TIMESTAMP()');
      $dbResult->bindValue(':auth_key', $authKey);      
    }
    
    $dbResult->bindTable(':table_users', TABLE_USERS);
    $dbResult->bindValue(':login', $login);
    $dbResult->bindValue(':password', $password);
    
    $dbResult->execute();
    if(System::database()->isError()) {
      throw new Exception(System::database()->getError());
    }
    
    if($dbResult->numberOfRows() > 0) {
      $roles = UserRoles::getInstance();
      $result = $dbResult->toArray();
      $result['role'] = $roles->getRole($dbResult->valueInt('role_id'));
    }
    return $result;
  }
  
  /**
   * Check if user with this credentials exist in database if user exists update it authKey 
   * @param string $login user login
   * @param string $password user password
   * @return boolean|User false if user does not exists otherwise instance of user class
   */
  public static function login($login, $password) {
    $user = new User($login, $password);
    
    if(!$user->id) {
      return false;
    }
    
    $user->authKey = Helper::getGUID();
    $user->authExpired = date('U') + System::Config()->loginExpTime;
    $user->lastLoginTime = date('U');
    $user->saveToDatabase();
    
    return $user;
  }
  
  /**
   * Check is user has valid auth key
   * @return boolean true if auth key valid
   */
  public function isUserValid() {
    $user = self::getUserFromDb($this->login, $this->password, $this->authKey);
    return $user != null;
  }
  
}

?>
