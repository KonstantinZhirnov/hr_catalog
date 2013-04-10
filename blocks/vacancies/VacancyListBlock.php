<?php
/**
 * Contains VacancyListBlock implementation
 *
 * @author Konstantin Zhirnov
 */

class VacancyListBlock extends BlockAbstract {
  private $vacancies;
  
  public function render() {
    $this->process();
    ob_start(); ?>
<form id='vacancyEdit'>
<table id='vacanciesList'>
  <tr>
    <th style='width:5%;'>Ид</th>
    <th style='width:60%;'>Название</th>
    <th style='width:15%;'>статус</th>
    <th></th>
  </tr>
<?php 
    $this->content .= ob_get_clean();
    foreach ($this->vacancies as $vacancy) {
      $this->content .= "<tr id='{$vacancy->id}'>";
      
      $itemBlock = new VacancyItemBlock();
      $itemBlock->process($vacancy);
      $itemBlock->item();
      $this->content .= $itemBlock->render();

      $this->content .= '</tr>';
    }
    ob_start() ?>
  <tr id='new'>
    <td></td>
    <td></td>
    <td></td>
    <td>
      <a href="javascript:void(0);" onclick="addItem('new');">добавить</a>
    </td>
  </tr>
</table>
</form>
  <?php
    $this->content .= ob_get_clean();
    return $this->content;
  }  
  
  /**
   * Implementation  of IBlockprocess method
   */
  public function process() {
    $this->vacancies = Vacancy::getItems();
  }
}

?>
