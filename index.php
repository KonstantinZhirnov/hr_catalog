<?php
require_once 'core/coreInitializer.php';


$modules = new ModuleChain();
//$modules->AddModule(new ErrorModule());
$modules->AddModule(new ConfigModule('config/config.php'));
$modules->AddModule(new DatabaseInitModule());
$modules->Process();

$db = System::database();
$dbResult = $db->query("select * from regions");
$res = $dbResult->execute();
$array = array();
if($dbResult->numberOfRows() > 0) {
  while ($dbResult->next()) {
    $array[] = $dbResult->toArray();
  }
}
Log::Show($array);
?>

