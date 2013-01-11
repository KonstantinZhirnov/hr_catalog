<?php

/**
 * module for login
 *
 * @author Konstantin Zhirnov
 */
class AuthorizationModule implements IModule {
  
  public function Run() {
    if(!$this->isLoggedIn()) {
      if(!$this->login()) {
        die('You need to authorize');
      }
    } 
  }
  
  public function __construct() {
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
      $user = User::login($_REQUEST['login'], $_REQUEST['password']);
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
