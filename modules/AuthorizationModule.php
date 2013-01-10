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
        Log::Show('You need to authorize');
      }
    }
  }
  
  public function __construct() {
    session_start();
    $this->addUserToSession();
  }
  
  private function isLoggedIn() {
    return System::CurrentUser() != null;
  }
  
  private function login() {
    if(!isset($_REQUEST['login']))
      return false;
    
    $user = User::login($_REQUEST['login'], $_REQUEST['password']);
    $_SESSION['current_user'] = $user;
    $this->addUserToSession();
    return true;
  }
  
  private function addUserToSession() {
    if(isset($_SESSION['current_user']))
      System::CurrentUser($_SESSION['current_user']);
  }
}

?>
