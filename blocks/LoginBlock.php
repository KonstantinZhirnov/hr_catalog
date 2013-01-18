<?php

/**
 * Description of LoginBlock
 *
 * @author Konstantin Zhirnov
 */
class LoginBlock extends BlockAbstract {
  private $module = null;
  
  /**
   * Implementation of IBlock render method
   * @return string block content
   */
  public function render() {
    $this->process();
    
    if(System::CurrentUser() == null || !System::CurrentUser()->IsUserValid()) {
      $this->content .= '<form method="POST">
        <input type="text" name="login" /></br>
        <input type="password" name="password" /></br>
        <input type="submit" name="login_submit" value="Вход" /></br>
        </form>';
    } else {
      $this->content .= "You'r logged in as " . System::CurrentUser()->login . "</br>";
      $this->content .= '<form method="POST">
        <input type="submit" name="logout" value="Выйти" />
        </form>';
    }
    return $this->content;
  }
  
  /**
   * Implementation of IBlock process method
   */
  public function process() {
    if(isset($_REQUEST['login'])) {
      $this->module->Run();
    }
    
    if(isset($_REQUEST['logout'])) {
      System::CurrentUser()->logout();
      $this->module->logout();
    }
  }
  
  public function __construct() {
    $this->module = AuthorizationModule::getInstance();
  }
}

?>
