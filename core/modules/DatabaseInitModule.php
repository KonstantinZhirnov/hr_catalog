<?php
/**
 * module for initialize database connection
 *
 * @author Konstantin Zhirnov
 */
class DatabaseInitModule implements IModule {
  /**
   * implementationn of IModule interface
   */
  public function Run() {
    $database = iDatabase_mysqli::getInstance();
    $database->connect(System::Config()->database['server'], System::Config()->database['user'], System::Config()->database['password']);
    $database->selectDatabase(System::Config()->database['name']);
    
    System::database($database);
  }
}

?>
