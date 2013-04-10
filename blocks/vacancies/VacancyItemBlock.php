<?php
/**
 * Contains VacancyEditBlock implementation
 *
 * @author Konstantin Zhirnov
 */
class VacancyItemBlock extends BlockAbstract {
  
  private $vacancy;
  
  public function render() {
    $this->process();
    $this->getHtml();
    return $this->content;
  }
  
  public function process($vacancy = null) {
    if(!isset($_REQUEST['id']) && !$vacancy) {
      return;
    }

    $this->vacancy = $vacancy ? $vacancy : Vacancy::getById($_REQUEST['id']);
  }
  
  private function getHtml() {
    if(!isset($_REQUEST['action'])) {
      return;
    }
    $actionName = $_REQUEST['action'];
    $this->$actionName();
  }
  
  public function edit() {
    ob_start()?>

  <td>
    <?php echo $this->vacancy->id?>
    <input type="hidden" id="id" name="id" value="<?php echo $this->vacancy->id?>" />
    <input type="hidden" id="action" name="action" value="save" />
    <input type="hidden" id="block" name="block" value="VacancyItem" />
  </td>
  <td><input type="text" id="name" name="name" value="<?php echo $this->vacancy->name?>" /></td>
  <td>
    <?php $activities = VacancyActivity::getItems();?>
    <select name='activity_id'>
      <?php foreach($activities as $activity) {?>
      <option value=<?php echo '"'.$activity->id.'"'; if($activity->id == $this->vacancy->activity['id']) {echo "selected='selected'";}?> ><?php  echo $activity->name;?></option>
      <?}?>
    </select>
  </td>
  <td>
    <a href="javascript:void(0);" onclick="saveItem(<?php echo $this->vacancy->id ? $this->vacancy->id : "'new'"; ?>);">сохранить</a>
    <a href="javascript:void(0);" onclick="getVacancyItem(<?php echo $this->vacancy->id; ?>);">отмена</a>
  </td>

    <?php
    $this->content .= ob_get_clean();
    return;
  }
  
  public function item() {
    ob_start()?>
<td><?php echo $this->vacancy->id; ?></td>
    <td><?php echo $this->vacancy->name; ?></td>
    <td><?php echo $this->vacancy->activity['name']; ?></td>
    <td>
      <a href="javascript:void(0);" onclick="getVacancyEdit(<?php echo $this->vacancy->id; ?>);">редактировать</a>
    </td>
    <?php
    $this->content .= ob_get_clean();
  }
  
  public function save() {
    $activity = VacancyActivity::getById($_REQUEST['activity_id']);
    $_REQUEST['activity_name'] = $activity->name;
    $this->vacancy->fillFromArray($_REQUEST);
    $this->vacancy->Save();
    $this->item();
  }
}

?>
