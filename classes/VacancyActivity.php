<?php

/**
 * Contains VacancyActivity implementation
 *
 * @author Konstantin Zhirnov
 */
class VacancyActivity extends DatabaseInteraction {
  protected $id;
  protected $name;
  
  protected static $_dabaseTable = TABLE_VACANCY_ACTIVITIES;
  
  public function fillFromArray($data) {
    if($data){
      
      $this->id = $data['id'];
      $this->name = $data['name'];
    }
  } 
  
  /**
   * get instance of Vacancy from database by id
   * @param int $id
   * @return Vacancy instance of Vacancy
   */
  public static function getById($id){
    static::getFromDB(array('id' => $id));
    
    if(static::$_dbResult->numberOfRows() > 1) {
      throw new Exception('Incorrect id. Multiple vacancies found');
    }
    $vacancy = new VacancyActivity();
    $vacancy->fillFromArray(static::$_dbResult->toArray());
    return $vacancy;
  }
}

?>
