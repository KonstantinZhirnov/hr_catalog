<?php

/**
 * Initialize database table names
 *
 * @author Konstantin Zhirnov
 */
class DatabaseTableInitModule implements IModule {
  /**
   * implementation of IModule interface
   */
  public function Run() {
    define('TABLE_CANDIDATES', 'candidates');
    define('TABLE_CITIES', 'cities');
    define('TABLE_CONTACTS', 'contacts');
    define('TABLE_EMPLOEES', 'emploees');
    define('TABLE_EMPLOEE_CONTACTS', 'emploee_contacts');
    define('TABLE_EMPLOEE_STATUSES', 'emploee_statuses');
    define('TABLE_QUALIFICATIONS', 'qualifications');
    define('TABLE_REGIONS', 'regions');
    define('TABLE_USERS', 'users');
    define('TABLE_USER_ROLES', 'user_roles');
    define('TABLE_VACANCIES', 'vacancies');
    define('TABLE_VACANCY_ACTIVITIES', 'vacancy_activities');
  }
}

?>
