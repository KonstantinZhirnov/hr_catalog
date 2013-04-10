<?
require_once 'AppInit.php';

Block::show("Header");

?>
<h1 class="header">Вакансии</h1>
  
<?
Block::show("VacancyList");

Block::show("Footer");
?>