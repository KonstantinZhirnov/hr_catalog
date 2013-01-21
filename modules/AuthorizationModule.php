<?php
/**
 * module for login
 *
 * @author Konstantin Zhirnov
 */
class AuthorizationModule implements IModule, ISingleton {
  
  private static $instance = null;
  
  public function Run() {
    if(!$this->isLoggedIn()) {
      if(!$this->login()) {
        return false;
      }
    } 
    return true;
  }
  
  public static function getInstance($param = null) {
    if(self::$instance === null) {
      self::$instance = new AuthorizationModule($param);
    }
    
    return self::$instance;
  }
  
  private function __construct() {
    if(session_id() == '')
      session_start();
    $this->addUserToSession();
  }
  
  private function isLoggedIn() {
    $isLoggedIn = true;
    if(System::CurrentUser() == null) {
      $isLoggedIn = false;
    } elseif (!System::CurrentUser()->isUserValid()) {
      $isLoggedIn = false;
    } elseif (isset($_REQUEST['authKey']) && System::CurrentUser()->authKey != $_REQUEST['authKey']) {
      $isLoggedIn = false;
    }
    
    return $isLoggedIn;
  }
  
  private function login() {
    $user = null;
    if(isset($_REQUEST['login']))
    {    
      $user = User::login($_REQUEST['login'], Helper::getMd5Hash($_REQUEST['password']));
    } elseif(isset($_REQUEST['authKey'])) {
      $user = User::getUserByKey($_REQUEST['authKey']);
      if($user) {
        $user = User::login($user->login, $user->password);
      }
    }
    if($user) {
      $this->addUserToSession($user);
      return true;
    }
    
    return false;
  }
  
  public function logout() {
    $_SESSION['current_user'] = null;
    System::CurrentUser(false);
  }
  
  private function addUserToSession($user = null) {
    if($user) {
      $_SESSION['current_user'] = $user;
    }
    
    if(isset($_SESSION['current_user'])) {
      System::CurrentUser($_SESSION['current_user']);
    }
  }
}

?>
