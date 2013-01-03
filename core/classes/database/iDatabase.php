<?php
/**
 * File iDatabase.php
 *
 * This file is part of Eldorado project
 *
 * The file contains an abstract class which is base for all classes working with a database
 *
 * PHP versions 5
 *
 * @copyright Copyright (c) 2010, iLogos
 */

  /**
   * iDatabase
   *
   * The iDatabase class contains methods for work with a database
   *
   * @copyright Copyright (c) 2010, iLogos
   */
  abstract class iDatabase {

    /**
     * The status connect
     * @var bool
     * @access public
     */
    public $is_connected = false;

    /**
     * Descriptor of connection with a database
     * @var resource
     * @access public
     */
    public $link;

    /**
     * Descriptor of connection with a database for an sql logger
     * @var resource
     * @access public
     */
    public $logger_link;

    /**
     * Descriptor of connection with a database for an sql logger
     * @var bool
     * @access public
     */
    public $sql_logging = false;

    /**
     * Contains value which specifies to show or to not show a mistake
     * @var bool
     * @access public
     */
    public $error_reporting = true;

    /**
     * The status error
     * @var bool
     * @access public
     */
    public $error = false;

    /**
     * Contains number of a line where there was a mistake
     * @var string
     * @access public
     */
    public $error_number;

    /**
     * Contains the text of a mistake which has occured in a database
     * @var string
     * @access public
     */
    public $error_query;

    /**
     * The name host
     * @var string
     * @access public
     */
    public $server;

    /**
     * The name user
     * @var string
     * @access public
     */
    public $username;

    /**
     * The password user
     * @var string
     * @access public
     */
    public $password;

    /**
     * The status mode debug
     * @var bool
     * @access public
     */
    public $debug = false;

    /**
     * Longest query time
     * @var float
     * @access public
     */
    public $max_query_time = 0;

    /**
     * The number of queries
     * @var int
     * @access public
     */
    public $number_of_queries = 0;

    /**
     * The time of queries
     * @var int
     * @access public
     */
    public $time_of_queries = 0;

    /**
     * The next id of record
     * @var int
     * @access public
     */
    public $nextID = null;

    /**
     * transaction
     * @var bool
     * @access public
     */
    public $logging_transaction = false;

    /**
     * transaction action
     * @var bool
     * @access public
     */
    public $logging_transaction_action = false;

    /**
     * connect
     *
     * Adstract method connect database
     *
     * @param string $server - name host
     * @param string $username - user name
     * @param string $password - password user
     *
     * @access public
     */
    abstract function connect($server, $username, $password);

    /**
     * setConnected
     *
     * Set of value to a field of a class which contains the status of connection
     *
     * @param bool $value - Logic value which contains true if has been connect with database
     *
     * @access public
     */
    function setConnected($value) {
      if ($value === true) {
        $this->is_connected = true;
      } else {
        $this->is_connected = false;
      }
    }

    /**
     * isConnected
     *
     * The method returns logic value which contains in a field is_connected of a class
     *
     * @return true - is connect, otherwise fasle
     *
     * @access public
     */
    function isConnected() {
      if ($this->is_connected === true) {
        return true;
      } else {
        return false;
      }
    }

    /**
     * query
     *
     * Adstract method for record of inquiry in a field of a class
     *
     * @param string $query - sql query
     *
     * @access public
     */
    abstract function &query($query);

    /**
     * setError
     *
     * In a method fields $error , $error_number, $error_query of a class are initialized
     *
     * @param string $error - text error
     * @param string $error_number - error number
     * @param string $query - The text of the message which has caused a mistake
     * @param string $error_class - class of message for MessageStack, debug is default value
     *
     * @access public
     */
    function setError($error, $error_number = '', $query = '', $error_class='debug') {

      if ($this->error_reporting === true) {
        $this->error = $error;
        $this->error_number = $error_number;
        $this->error_query = $query;

        iLog::add($this->getError(), 'error');
      }
    }

    /**
     * isError
     *
     * The method returns value of a field error
     *
     * @return true - is error, otherwise false
     *
     * @access public
     */
    function isError() {
      if ($this->error === false) {
        return false;
      } else {
        return true;
      }
    }

    /**
     * getError
     *
     * The method returns the text of a mistake
     *
     * @return The method returns the text of a mistake, otherwise false
     *
     * @access public
     */
    function getError() {
      if ($this->isError()) {
        $error = '';

        if (!empty($this->error_number)) {
          $error .= $this->error_number . ': ';
        }

        $error .= $this->error;

        if (!empty($this->error_query)) {
          $error .= '; ' . htmlentities($this->error_query);
        }

        return $error;
      } else {
        return false;
      }
    }

    /**
     * setErrorReporting
     *
     * In a method fields $error_reporting of a class are initialized
     *
     * @param bool $boolean - Contains value true if it is necessary to show the text of a mistake
     *
     * @access public
     */
    function setErrorReporting($boolean) {
      if ($boolean === true) {
        $this->error_reporting = true;
      } else {
        $this->error_reporting = false;
      }
    }

    /**
     * setDebug
     *
     * Set mode debug
     *
     * @param bool $boolean - contains value true set mode debug, false close mode debug
     *
     * @access public
     */
    function setDebug($boolean) {
      if ($boolean === true) {
        $this->debug = true;
      } else {
        $this->debug = false;
      }
    }

    /**
     * hasCreatePermission
     *
     * Checked permission
     *
     * @param string $database - name database
     *
     * @return In case of success returns a true, otherwise false
     *
     * @access public
     */
    function hasCreatePermission($database) {
      $db_created = false;

      if (empty($database)) {
        $this->setError(ERROR_DB_NO_DATABASE_SELECTED);

        return false;
      }

      $this->setErrorReporting(false);

      if ($this->selectDatabase($database) === false) {
        $this->setErrorReporting(true);

        if ($this->simpleQuery('create database ' . $database)) {
          $db_created = true;
        }
      }

      $this->setErrorReporting(true);

      if ($this->isError() === false) {
        if ($this->selectDatabase($database)) {
          if ($this->simpleQuery('create table fxljfdsljdflsdk ( temp_id int )')) {
            if ($db_created === true) {
              $this->simpleQuery('drop database ' . $database);
            } else {
              $this->simpleQuery('drop table fxljfdsljdflsdk');
            }
          }
        }
      }

      if ($this->isError()) {
        return false;
      } else {
        return true;
      }
    }

    /**
     * getLock
     *
     * The method sets database lock and reports if it was not set successfull
     *
     * @param string $name - lock name
     * @param int $param - timeout to wait for lock
     *
     * @return boolean - if lock is set returns true
     *
     * @access public
     */
    public function getLock($name, $param = 0) {
      $iDatabase = iFactory::singleton('iDatabase_'.DB_DATABASE_CLASS);

      $Qlock = $iDatabase->query("SELECT GET_LOCK('".$name."', '".$param."') as l");
      $Qlock->execute();

      if ($Qlock->numberOfRows() > 0)
        if ($Qlock->value('l') == 1)
          return true;
        else
          return false;
      else
        return false;
    }

    /**
     * checkLock
     *
     * The method sets database lock and reports if it was not set successfull
     *
     * @param string $name - lock name
     * @param int $cycle_count - count of cycle for wait lock
     * @param int $cycle_period - one cycle period
     *
     * @return boolean - if lock is free returns true
     *
     * @access public
     */
    public function checkLock($name, $cycle_count = 1, $cycle_period = 500) {
      $iDatabase = iFactory::singleton('iDatabase_' . DB_DATABASE_CLASS);

      while ($cycle_count > 0) {

        $cycle_count--;

        $Qlock = $iDatabase->query("SELECT IS_FREE_LOCK('".$name."') AS l");
        $Qlock->execute();

        if ($Qlock->valueInt('l') === 1) {
          return true;
        } else {
          if ($cycle_count > 0) {
            usleep($cycle_period * 1000);
          } else {
            return false;
          }
        }

      }//while
    }

    /**
     * releaseLock
     *
     * The method releases database lock by it's name
     *
     * @param string $name - lock name
     *
     * @access public
     */
    public function releaseLock($name) {
      $iDatabase = iFactory::singleton('iDatabase_'.DB_DATABASE_CLASS);

      $Qlock = $iDatabase->query("SELECT RELEASE_LOCK('".$name."')");
      $Qlock->execute();
    }

    /**
     * numberOfQueries
     *
     * The method return number line of queries
     *
     * @return number line of queries
     *
     * @access public
     */
    function numberOfQueries() {
      return $this->number_of_queries;
    }

    /**
     * timeOfQueries
     *
     * The method return time execution query
     *
     * @return time execution query
     *
     * @access public
     */
    function timeOfQueries() {
      return $this->time_of_queries;
    }

    /**
     * maxQueryTime
     *
     * The method return execution time of the logest query
     *
     * @return execution query time
     *
     * @access public
     */
    function maxQueryTime() {
      return $this->max_query_time;
    }

    /**
     * resetMaxQueryTime
     *
     * The method resets execution time of the logest query
     *
     * @return execution query time
     *
     * @access public
     */
    function resetMaxQueryTime() {
      $this->max_query_time = 0;
    }

    /**
     * clearFileCache
     * Function that clear file query cache
     *
     * @access public
     */
    public function clearFileCache() {
      $dir = opendir(DB_CACHE_DIR);
      while(($file = readdir($dir))) {
        if ( is_file (DB_CACHE_DIR . $file)) {
          @unlink(DB_CACHE_DIR .$file);
        }
      }
    }

  }

  class iDatabase_Result {

    var $db_class,
        $sql_query,
        $query_handler,
        $result,
        $rows,
        $affected_rows,
        $cache_key,
        $cache_expire,
        $cache_data,
        $cache_read = false,
        $debug = false,
        $batch_query = false,
        $batch_number,
        $batch_rows,
        $batch_size,
        $batch_to,
        $batch_from,
        $batch_select_field,
        $logging = false,
        $logging_module,
        $logging_module_id,
        $file_cache_use = false,
        $file_cache_expire,
        $logging_fields = array(),
        $logging_changed = array();

    /**
     * __construct
     *
     * This function is construct database result
     *
     * @param object $db_class - object of class database
     *
     * @access public
     */
    public function __construct(&$db_class) {
      $this->db_class =& $db_class;
    }

    /**
     * setQuery
     *
     * This function for set query
     *
     * @param string $query - query sql
     *
     * @access public
     */
    public function setQuery($query) {
      $this->sql_query = $query;
    }

    /**
     * appendQuery
     *
     * This function for append query
     *
     * @param string $query - part of query
     *
     * @access public
     */
    public function appendQuery($query) {
      $this->sql_query .= ' ' . $query;
    }

    /**
     * getQuery
     *
     * This function for get query
     *
     * @return query
     *
     * @access public
     */
    public function getQuery() {
      return $this->sql_query;
    }

    /**
     * setDebug
     *
     * This function for set debug
     *
     * @param boolean $boolean - true for enable debug of sql
     *
     * @access public
     */
    public function setDebug($boolean) {
      if ($boolean === true) {
        $this->debug = true;
      } else {
        $this->debug = false;
      }
    }

    /**
     * valueMixed
     *
     * This function for parse mixed value
     *
     * @param string $column - column of table
     * @param string $type - type of variable
     *
     * @return value in accordance with the type
     *
     * @access public
     */
    public function valueMixed($column, $type = 'string') {
      if (!isset($this->result)) {
        $this->next();
      }

      switch ($type) {
        case 'protected':
          return iOutputStringProtected($this->result[$column]);
          break;
        case 'int':
          return (int)$this->result[$column];
          break;
        case 'decimal':
          return (float)$this->result[$column];
          break;
        case 'bool':
          return $this->result[$column] == "\x01" ? true : false;
          break;
        case 'string':
        default:
          // TODO: must be thorroughthly tested
          if (isset($this->result[$column])) {
            return $this->result[$column];
          } else {
            return null;
          }
      }
    }

    /**
     * value
     *
     * This function for return value from column table
     *
     * @param string $column - column of table
     *
     * @return value of column table
     *
     * @access public
     */
    public function value($column) {
      return $this->valueMixed($column, 'string');
    }

    /**
     * valueProtected
     *
     * This function for return protected value from column table
     *
     * @param string $column - column of table
     *
     * @return protected value of column table
     *
     * @access public
     */
    public function valueProtected($column) {
      return $this->valueMixed($column, 'protected');
    }

    /**
     * valueInt
     *
     * This function for return int value from column table
     *
     * @param string $column - column of table
     *
     * @return int value of column table
     *
     * @access public
     */
    public function valueInt($column) {
      return $this->valueMixed($column, 'int');
    }

    /**
     * valueBool
     *
     * This function for boolean value from column table
     *
     * @param string $column - column of table
     *
     * @return boolean value of column table
     *
     * @access public
     */
    public function valueBool($column) {
      return $this->valueMixed($column, 'bool');
    }

    /**
     * valueDecimal
     *
     * This function for return decimal value from column table
     *
     * @param string $column - column of table
     *
     * @return decimal value of column table
     *
     * @access public
     */
    public function valueDecimal($column) {
      return $this->valueMixed($column, 'decimal');
    }

    /**
     * bindValueMixed
     *
     * This function for bind value mixed
     *
     * @param string $place_holder - placeholder
     * @param mixed $value - value
     * @param string $type  - value type
     * @param boolean $trim  - if true - function trim will be applied to the value
     * @param boolean $bindTable - if false value will add in logging fields
     *
     * @access public
     */
    public function bindValueMixed($place_holder, $value, $type = 'string', $trim = true) {
      if ($trim && !is_null($value)) {
        $value = trim($value);
      }

      if ($type != 'aes')
        $this->logging_fields[substr($place_holder, 1)] = $value;

      if (is_null($value)) {
        $value = 'NULL';
      } else {
        switch ($type) {
          case 'int':
            $value = intval($value);
            break;
          case 'float':
            $value = floatval($value);
            break;
          case 'aes':
            $place_holder_key = '@key_' . md5($place_holder);
            $this->db_class->simpleQuery('SELECT ' . $place_holder_key . ' := COMPRESS(\'' . $this->db_class->parseString($value) . '\')');
            $value = $place_holder_key;
            break;
          case 'raw':
            break;
          case 'string':
          default:
            $value = "'" . $this->db_class->parseString($value) . "'";
        }
      }

      $this->bindReplace($place_holder, $value);
    }

    /**
     * bindReplace
     *
     * This function for replace symbols in value
     *
     * @param string $place_holder - placeholder
     * @param mixed $value - value
     *
     * @access public
     */
    public function bindReplace($place_holder, $value) {

      $pos = strpos($this->sql_query, $place_holder);

      if ($pos !== false) {

        /*$iModulesLoggingSql = iFactory::singleton('iModulesLoggingSql', false, DIR_INCLUDES, 'logging');
        if (isset($iModulesLoggingSql)) {
          if ($iModulesLoggingSql->isEnabled()) {
            $iModulesLoggingSql->insertBoundValue($place_holder, $value);
          }
        }*/
        $value           = str_replace('\\', '\\\\', $value);
        $this->sql_query = preg_replace('/'.$place_holder.'(\s+|[ ,)"]+|$)/s', "$value$1", $this->sql_query);
        //$place_holder = ltrim($place_holder, ':');
        //$this->sql_query = preg_replace('/:\b' . $place_holder . '\b/i', $value , $this->sql_query);
      }

    }

    /**
     * bindValue
     *
     * This function for bind string value
     *
     * @param string $place_holder - placeholder
     * @param string $value - value of string
     * @param boolean $trim - if true - function trim will be applied to the value
     *
     * @access public
     */
    public function bindValue($place_holder, $value, $trim = true) {
      $this->bindValueMixed($place_holder, $value, 'string', $trim);
    }

    /**
     * bindAES
     *
     * This function for security bind string value
     *
     * @param string $place_holder - placeholder
     * @param string $value - value of string
     * @param boolean $trim - if true - function trim will be applied to the value
     *
     * @access public
     */
    public function bindAES($place_holder, $value, $trim = true) {
      $this->bindValueMixed($place_holder, $value, 'aes', $trim);
    }

    /**
     * bindLanguageJoin
     *
     * @param string $place_holder - placeholder
     * @param string $table - table that need title or description
     * @param string $table_short - short table name
     * @param string $joinField - join field
     * @param string $bindField - bind field title/description
     * @param string $table_join_prefix - if languages definitions table already join, add short name of previus join table
     * @param bool $trim - if true - function trim will be applied to the value
     *
     * @access public
     */
    public function bindLanguageJoin($place_holder, $table, $table_short, $joinField, $bindField = 'title', $table_join_prefix = '', $trim = true) {
      $table_prefix = substr($table, DB_TABLE_PREFIX_LEN) . '/';

      $value = 'LEFT JOIN ' . TABLE_LANGUAGES_DEFINITIONS . ' AS ' . $table_short . '
                  ON ' . $table_short . '.`key` = CONCAT(\'' . $table_prefix . '\', ' . $joinField . ', \'/' . $bindField . '\')
                  AND ' . $table_short . '.`group` = \'database\'';
      if ($bindField == 'description' && !empty($table_join_prefix)) {
        $value .= ' AND ' . $table_short . '.idLanguages = ' . $table_join_prefix . '.idLanguages';
      }
      if ($trim) {
        $value = trim($value);
      }

      $this->bindReplace($place_holder, $value);
    }



    /**
     * bindInt
     *
     * This function for bind int value
     *
     * @param string $place_holder - placeholder
     * @param int $value - value of int
     *
     * @access public
     */
    public function bindInt($place_holder, $value) {
      $this->bindValueMixed($place_holder, $value, 'int');
    }

    /**
     * bindFloat
     *
     * This function for bind float value
     *
     * @param string $place_holder - placeholder
     * @param float $value - value of float
     *
     * @access public
     */
    public function bindFloat($place_holder, $value) {
      $this->bindValueMixed($place_holder, $value, 'float');
    }

    /**
     * bindRaw
     *
     * This function for bind raw value
     *
     * @param string $place_holder - placeholder
     * @param  mixed $value - value of mixed
     *
     * @access public
     */
    public function bindRaw($place_holder, $value) {
      $this->bindValueMixed($place_holder, $value, 'raw');
    }

    /**
     * bindTable
     *
     * This function for bind table
     *
     * @param string $place_holder - placeholder
     * @param string $value - name of table
     *
     * @access public
     */
    public function bindTable($place_holder, $value) {
      $this->bindReplace($place_holder, $value);
    }

    /**
     * next
     *
     * This function for get next value of result query
     *
     * @access public
     */
    public function next() {

      if ($this->cache_read === true) {
        list(, $this->result) = each($this->cache_data);
      } else {
        if (!isset($this->query_handler)) {
          $this->execute();
        }
        $this->result = $this->db_class->next($this->query_handler);
      }

      return $this->result;
    }

    /**
     * freeResult
     *
     * This function for clear the data query
     *
     * @access public
     */
    public function freeResult() {
      if ($this->cache_read === false) {
        if (preg_match('/^SELECT/i', $this->sql_query)) {
          $this->db_class->freeResult($this->query_handler);
        }
      }

      unset($this);
    }

    /**
     * numberOfRows
     *
     * This function for return count of rows in result of query
     *
     * @return count of rows in result of query
     *
     * @access public
     */
    public function numberOfRows() {

      if (!isset($this->rows)) {
        if (!isset($this->query_handler)) {
          $this->execute();
        }

        if (isset($this->cache_read) && ($this->cache_read === true)) {
          $this->rows = sizeof($this->cache_data);
        } else {
          $this->rows = $this->db_class->numberOfRows($this->query_handler);
        }
      }

      return $this->rows;
    }

    /**
     * affectedRows
     *
     * This function gets the number of affected rows in a previous sql operation
     *
     * @return Returns the number of affected rows on success, and -1 if the last query failed.
     *
     * @access public
     */
    public function affectedRows() {
      if (!isset($this->affected_rows)) {
        if (!isset($this->query_handler)) {
          $this->execute();
        }

        $this->affected_rows = $this->db_class->affectedRows();
      }

      return $this->affected_rows;
    }

    /**
     * execute
     *
     * Executing current query
     *
     * @return result of query
     *
     * @access public
     */
    public function execute() {
      if ($this->file_cache_use) {
        $file_cache_key = md5($this->sql_query);
        if (file_exists(DB_CACHE_DIR . $file_cache_key)) {
          $filetime = @filemtime(DB_CACHE_DIR . $file_cache_key);
          if ($filetime !== false) {
            if (($filetime + $this->file_cache_expire) > time()) {
              $this->cache_data = unserialize(file_get_contents(DB_CACHE_DIR . $file_cache_key));
              $this->cache_read = true;
              return;
            }
          }
        }
      }

      // Actually running the query
      $this->query_handler = $this->db_class->simpleQuery($this->sql_query, $this->debug);

      // Working with batched query
      if ($this->query_handler !== false) {
        if ($this->batch_query === true) {

          $this->batch_size = $this->db_class->getBatchSize($this->sql_query, $this->batch_select_field);
          $this->batch_to   = ($this->batch_rows * $this->batch_number);
          $this->batch_from = ($this->batch_rows * ($this->batch_number - 1));

          if ($this->batch_to > $this->batch_size) {
            $this->batch_to = $this->batch_size;
          }

          if ($this->batch_to == 0) {
            $this->batch_from = 0;
          } else {
            $this->batch_from++;
          }
        }

        if ($this->file_cache_use) {
          $data = array();
          if ($this->db_class->numberOfRows($this->query_handler) > 0) {
            $r = $this->db_class->next($this->query_handler);
            do {
              $data[] = $r;
              $r  = $this->db_class->next($this->query_handler);
            } while ($r);
            $this->db_class->dataSeek(0, $this->query_handler);
          }

          file_put_contents(DB_CACHE_DIR . $file_cache_key, serialize($data));
        }
      }

      // Returning the result
      return $this->query_handler;
    }

    /**
     * executeRandom
     *
     * This function is execute random
     *
     * @return result of query
     *
     * @access public
     */
    public function executeRandom() {
      return $this->query_handler = $this->db_class->randomQuery($this->sql_query);
    }

    /**
     * executeRandomMulti
     *
     * This function is execute random multi
     *
     * @return result of query
     *
     * @access public
     */
    public function executeRandomMulti() {
      return $this->query_handler = $this->db_class->randomQueryMulti($this->sql_query);
    }

    /**
     * setFileCacheUse
     *
     * This function for set file cache use flag
     *
     * @param bool $use - key of cache
     * @param int $expiteTime  - time of expire
     *
     * @access public
     */
    public function setFileCacheUse($use, $expiteTime) {
      $this->file_cache_use    = $use;
      $this->file_cache_expire = $expiteTime;
    }

    /**
     * setCache
     *
     * This function for set cache
     *
     * @param string $key - key of cache
     * @param int $expire  - time of expire
     *
     * @access public
     */
    public function setCache($key, $expire = 0) {
      $this->cache_key = $key;
      $this->cache_expire = $expire;
    }

    /**
     * setLogging
     *
     * This function for set logging
     *
     * @param string $module - module name
     * @param int $id  - module id
     *
     * @access public
     */
    public function setLogging($module, $id = null) {

      $this->logging           = true;
      $this->logging_module    = $module;
      $this->logging_module_id = $id;

    }

    /**
     * setNextID
     *
     * This function for set next id
     *
     * @param int $id - next id
     *
     * @access public
     */
    public function setNextID($id) {
      $this->db_class->nextID = $id;
    }

    /**
     * toArray
     *
     * The function returns a result query as an array
     *
     * @access public
     */
    public function toArray() {
      if (!isset($this->result)) {
        $this->next();
      }

      return $this->result;
    }

    /**
     * prepareSearch
     *
     * This function for prepare search
     *
     * @param string $keywords - keywords
     * @param array $columns - columns of table
     * @param bool $embedded  - if value true -  in beginning of query will be added AND
     *
     * @access public
     */
    public function prepareSearch($keywords, $columns, $embedded = false) {

      if ($embedded === true) {
        $this->sql_query .= ' AND ';
      }

      $keywords_array = explode(' ', $keywords);

      if ($this->db_class->use_fulltext === true) {
        if ($this->db_class->use_fulltext_boolean === true) {
          $keywords = '';

          foreach ($keywords_array as $keyword) {
            if ((substr($keyword, 0, 1) != '-') && (substr($keyword, 0, 1) != '+')) {
              $keywords .= '+';
            }

            $keywords .= $keyword . ' ';
          }

          $keywords = substr($keywords, 0, -1);
        }

        $this->sql_query .= $this->db_class->prepareSearch($columns);
        $this->bindValue(':keywords', $keywords);
      } else {
        foreach ($keywords_array as $keyword) {
          $this->sql_query .= $this->db_class->prepareSearch($columns);

          foreach ($columns as $column) {
            $this->bindValue(':keyword', '%' . $keyword . '%');
          }

          $this->sql_query .= ' AND ';
        }

        $this->sql_query = substr($this->sql_query, 0, -5);
      }
    }

    /**
     * setBatchLimit
     *
     * This function for set batch limit
     *
     * @param int $batch_number  - with what element to begin
     * @param int $maximum_rows  - quantity of taken elements
     * @param string $select_field  - select field
     *
     * @access public
     */
    public function setBatchLimit($batch_number = 1, $maximum_rows = 20, $select_field = '') {

      $this->batch_query = true;

      $this->batch_number = (is_numeric($batch_number) ? $batch_number : 1);
      $this->batch_rows = $maximum_rows;
      $this->batch_select_field = (empty($select_field) ? '*' : $select_field);

      $from = max(($this->batch_number * $maximum_rows) - $maximum_rows, 0);

      $this->sql_query = $this->db_class->setBatchLimit($this->sql_query, $from, $maximum_rows);

    }

    /**
     * getBatchSize
     *
     * This function for get batch size
     *
     * @access public
     */
    public function getBatchSize() {
      return $this->batch_size;
    }

    /**
     * isBatchQuery
     *
     * This function for get boolean value if query is batch
     *
     * @return true - if query is batch, otherwise false
     *
     * @access public
     */
    public function isBatchQuery() {
      if ($this->batch_query === true) {
        return true;
      }

      return false;
    }

    /**
     * getBatchTotalPages
     *
     * This function is get batch total pages
     *
     * @param string $text - text for generate string total pages
     *
     * @return generated string total pages
     *
     * @access public
     */
    public function getBatchTotalPages($text) {
      return sprintf($text, $this->batch_from, $this->batch_to, $this->batch_size);
    }

    /**
     * getBatchPageLinks
     *
     * This function for get batch page links
     *
     * @param string $batch_keyword  - batch keyword
     * @param string $parameters  - parameters
     *
     * @return array data of pages links
     *
     * @access public
     */
    public function getBatchPageLinks($batch_keyword = 'page', $parameters = '') {

      $batch_page_links_array = array();

      $batch_page_links_array['previous'] = $this->getBatchPreviousPageLink($batch_keyword, $parameters);

      $batch_page_links_array['pages'] = $this->getBatchPagesMenu($batch_keyword, $parameters);

      $batch_page_links_array['next'] = $this->getBatchNextPageLink($batch_keyword, $parameters);

      return $batch_page_links_array;
    }

    /**
     * getBatchPagingLinks
     *
     * The function for get batch paging links
     *
     * @param string $batch_keyword - batch keyword
     * @param string $parameters - parameters
     *
     * @return string paging links
     *
     * @return string
     */
    public function getBatchPagingLinks($batch_keyword = 'page', $parameters = '') {

      $get_parameter = '';
      $number_of_pages = ($this->batch_rows != 0)?ceil($this->batch_size / $this->batch_rows):0;

      if ( !empty($parameters) ) {
        $parameters = explode('&', $parameters);

        foreach ( $parameters as $parameter ) {
          $keys = explode('=', $parameter, 2);

          if ( $keys[0] != $batch_keyword ) {
            $get_parameter .= $keys[0] . (isset($keys[1]) ? '=' . $keys[1] : '') . '&';
          }
        }
      }

      if ( $this->batch_number > 1 ) {
        $string = iLinkObject(iHrefLink(basename($_SERVER['SCRIPT_FILENAME']), $get_parameter . $batch_keyword . '=' . ($this->batch_number - 1)), iIcon('previous.png'), 'id="previousPage"');
      } else {
        $string = iIcon('previous_disabled.png');
      }

      $string .= '&nbsp;&nbsp;';

      if ( ( $this->batch_number < $number_of_pages ) && ( $number_of_pages != 1 ) ) {
        $string .= iLinkObject(iHrefLink(basename($_SERVER['SCRIPT_FILENAME']), $get_parameter . $batch_keyword . '=' . ($this->batch_number + 1)), iIcon('next.png'), 'id="nextPage"');
      } else {
        $string .= iIcon('next_disabled.png');
      }

      return $string;
    }
  }
?>