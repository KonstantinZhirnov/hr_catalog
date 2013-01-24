<?php
require_once 'AppInit.php';

$headers = new HeaderBlock();
print $headers->render();

//$loginBlock = new LoginBlock();
//print $loginBlock->render();
//Log::Save('just do it');
//Log::Show(Vacancy::searchByName("и"));

//$vacancy = new Vacancy();
//$vacancy->activityId = 1;
//$vacancy->name = "test from php in " . date("U");
//$vacancy->Save();

//Log::Show(Vacancy::searchByName('test'));

$conditions = array('activity_id' => Vacancy::ACTIVE);
$conditions['name'] = array('condition'=>'like', 'value'=>'%from%');

$testSave = Vacancy::getItems($conditions);

$content = "<table border='0'><tr><th>id<th><th>name<th></tr>";
foreach($testSave as $vacancy) {
  $content .= "<tr><td>{$vacancy->id}</td><td>{$vacancy->name}</td></tr>";
}
$content .= "</table>";
print $content;


$testQualification = City::getItems();
$contentQualification = "<table border='0'><tr><th>id<th><th>name<th></tr>";
foreach($testQualification as $vacancy) {
  $region = current($vacancy);
  $contentQualification .= "<tr><th colspan='2'>{$region->region->name}</th></tr>";
  
  foreach($vacancy as $city) {
    Log::Show($city->name, true);
    $contentQualification .= "<tr><td>{$city->id}</td><td>{$city->name}</td></tr>";
  }
}
$contentQualification .= "</table>";
print $contentQualification;


$footer = new FooterBlock();
$footer->render()


?>