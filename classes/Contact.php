<?php

/**
 * Working with emploee contacts
 *
 * @author Konstantin Zhirnov
 */
class Contact extends DatabaseInteraction {
  const EMAIL = 'email';
  const SKYPE = 'skype';
  const PHONE = 'phone';
  
  protected $id;
  protected $type;
  protected $value;
  
  protected static $_databaseTable = TABLE_CONTACTS;
  
  public function fillFromArray($data) {
    if(!is_array($data) || !($data)) {
      return;
    }
    
    $this->id = $data['id'];
    $this->type = $data['type'];
    $this->value = $data['contact'];
  }
  
  public static function getForUser($userId) {
    $result = array();
    
    self::$_dbResult = System::database()->query('select c.* from :db_table c
      where c.candidate_id = :user_id or c.emploee_id = :user_id
      order by c.candidate_id, c.emploee_id, c.type');
    self::$_dbResult->bindTable(":db_table", static::$_databaseTable);
    self::$_dbResult->bindTable(":table_user_contacts", TABLE_EMPLOEE_CONTACTS);
    self::$_dbResult->bindValue(':user_id', $userId);
    self::$_dbResult->execute();
    
    while(self::$_dbResult->Next()){
      $contact = new Contact();
      $contact->fillFromArray(self::$_dbResult->toArray());
      $result[] = $contact;
    }
    
    return $result;
  }
  
  public function Save($conditions) {
    $conditions = array('id' => $this->id, 'contact' => $this->value, );
    parent::SaveToDatabase($conditions);
  }
}

?>
