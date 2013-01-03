<?php
/**
 * File iDatabase_mysql.php
 *
 * This file is part of Eldorado project
 *
 * The file contains a class which contains methods for work with a database
 *
 * PHP versions 5
 *
 * @copyright Copyright (c) 2010, iLogos
 */

  /**
   * iDatabase_mysql
   *
   * The iDatabase_mysql class contains methods for work with a database
   * @copyright Copyright (c) 2010, iLogos
   *
   * @see iDatabase
   * @see iISingleton
   */
  class iDatabase_mysql extends iDatabase implements iSingleton  {

    /**
     * contains object of a class
     * @var object
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * use treansactions
     * @var boolean
     * @access public
     */
    public $use_transactions = false;

    /**
     * use full text for search
     * @var boolean
     * @access public
     */
    public $use_fulltext = false;

    /**
     * use fulltext
     * @var bollean
     * @access public
     */
    public $use_fulltext_boolean = false;

    /**
     * escape string
     * @var string
     * @access public
     */
    public $sql_parse_string = 'mysql_escape_string';

    /**
     * parse string with connection handler
     * @var bool
     * @access public
     */
    public $sql_parse_string_with_connection_handler = false;

    /**
     * __construct
     *
     * construct class
     *
     * @access protected
     */
    protected function __construct() {
      define('ERROR_SQL_FILE_NONEXISTENT', 'File not exists');
      define('ERROR_SQL_FILE_IS_EMPTY',    'File is empty');
    }

    /**
     * getInstance
     *
     * Method for reception of object of a class
     *
     * @param mixed $param - param
     *
     * @return object of a class
     *
     * @access public
     */
    public static function getInstance($param = false) {
      if(!self::$_instance){
        self::$_instance = new iDatabase_mysql();
      }
      return self::$_instance;
    }

    /**
     * connect
     *
     * The method establishes connection
     *
     * @param string $server - name server
     * @param string $username - name user database
     * @param string $password - password user database
     *
     * @return  true - In case of success otherwise false
     *
     * @access public
     */
    public function connect($server, $username, $password/*, $type = DB_DATABASE_CLASS*/) {
      $this->server   = $server;
      $this->username = $username;
      $this->password = $password;

      if (function_exists('mysql_real_escape_string')) {
        $sql_parse_string = 'mysql_real_escape_string';
        $sql_parse_string_with_connection_handler = true;
      }

      if ($this->isConnected() === false) {
        if (defined('USE_PCONNECT') && (USE_PCONNECT == 'true')) {
          $connect_function = 'mysql_pconnect';
        } else {
          $connect_function = 'mysql_connect';
        }

        if ($this->link = @$connect_function($server, $username, $password, true)) {
          $this->setConnected(true);
          mysql_query("SET NAMES 'utf8'", $this->link);

          return true;
        } else {
          $this->setError(mysql_error(), mysql_errno());

          return false;
        }
      }
    }

    /**
     * getLastInsertId
     *
     * Get last insert ID
     *
     * @return int - last insert ID
     *
     * @access public
     */
    public function getLastInsertId() {
      $id = intval(mysql_insert_id($this->link));
      if (!$id) {
        $id = $this->nextID;
      }
      return $id;
    }

    /**
     * disconnect
     *
     * The method closes connection
     *
     * @return true - In case of success otherwise false
     *
     * @access public
     */
    public function disconnect() {
      if ($this->isConnected()) {
        if (@mysql_close($this->link)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }

    /**
     * selectDatabase
     *
     * In a method the choice of a database is carried out
     *
     * @param string $database - name database
     *
     * @return true - In case of success otherwise false
     *
     * @access public
     */
    public function selectDatabase($database) {
      if ($this->isConnected()) {
        if (@mysql_select_db($database, $this->link)) {
          return true;
        } else {
          $this->setError(mysql_error($this->link), mysql_errno($this->link));
          return false;
        }
      } else {
        return false;
      }
    }

    /**
     * parseString
     *
     * Escapes special characters in a string for use in a SQL statement, taking into account the current charset of the connection
     *
     * @param string $value - the string to be escaped.
     *
     * @return returns an escaped string.
     *
     * @access public
     */
    function parseString($value) {
      if ($this->sql_parse_string_with_connection_handler === true) {
        return call_user_func_array($this->sql_parse_string, array($value, $this->link));
      } else {
        return call_user_func_array($this->sql_parse_string, array($value));
      }
    }

    /**
     * simpleQuery
     *
     * Send a sql query
     *
     * @param string $query - sql query
     * @param bool $debug - boolean
     *
     * @return In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function simpleQuery($query, $debug = false) {

      // return nothing if we are not connected
      if (!$this->isConnected()) {
        return false;
      }

      if (defined('DEBUG_OUTPUT_DB_QUERIES') && DEBUG_OUTPUT_DB_QUERIES == true || $debug) {
        $debug = true;

        ++$this->number_of_queries;
        $query_start_time = microtime(1);
      }

      $resource = @mysql_query($query, $this->link); //or trigger_error('MySQL Error: '.mysql_error($this->link));

      if ($resource === false) {
        trigger_error($query . "\nERROR " . mysql_errno($this->link) . ": " . mysql_error($this->link));
//        if (defined('DEBUG_SHOW_ERROR_QUERY') && DEBUG_SHOW_ERROR_QUERY == 1) {
          $this->setError(mysql_error($this->link), mysql_errno($this->link), $query);
//        }
      } else {
        $this->error        = false;
        $this->error_number = null;
        $this->error_query  = null;

        //$this->nextID = $this->getLastInsertId();
      }

      if ($debug === true) {
        $query_time = number_format(microtime(1) - $query_start_time, 7);
    
        $this->time_of_queries += $query_time;
        if ($this->max_query_time < $query_time) {
          $this->max_query_time = $query_time;
        }

        iLog::add('#' . $this->number_of_queries . ' - ' . $query_time . 's] ' . $query, 'warning');
      }

      return $resource;
    }

    /**
     * dataSeek
     *
     * Move internal result pointer
     *
     * @param int $row_number - The result resource that is being evaluated. This result comes from a call to mysql_query().
     * @param object $resource - The desired row number of the new result pointer.
     *
     * @return Returns TRUE on success or FALSE on failure.
     *
     * @access public
     */
    public function dataSeek($row_number, $resource) {
      return @mysql_data_seek($resource, $row_number);
    }

    /**
     * randomQuery
     *
     * Addition of sorting
     *
     * @param string $query - sql query
     *
     * @return In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function randomQuery($query) {
      $query .= ' ORDER BY RAND() LIMIT 1';
      return $this->simpleQuery($query);
    }

    /**
     * randomQueryMulti
     *
     * random multi query
     *
     * @param string $query - sql query
     *
     * @return In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function randomQueryMulti($query) {
      $resource = $this->simpleQuery($query);

      $num_rows = $this->numberOfRows($resource);

      if ($num_rows > 0) {
        $random_row = i_rand(0, ($num_rows - 1));

        $this->dataSeek($random_row, $resource);

        return $resource;
      } else {
        return false;
      }
    }

    /**
     * next
     *
     * Extraction of data received from database
     *
     * @param object $resource - The received descriptor
     *
     * @return returns an associative file with names of indexes, relevant to names of columns or FALSE if numbers are not present more.
     *
     * @access public
     */
    public function next($resource) {
      return @mysql_fetch_assoc($resource);
    }

    /**
     * freeResult
     *
     * Free result memory
     *
     * @param object $resource - The result resource that is being evaluated. This result comes from a call to mysql_query().
     *
     * @return Returns TRUE on success or FALSE on failure.
     *
     * @access public
     */
    public function freeResult($resource) {
      if (@mysql_free_result($resource)) {
        return true;
      } else {
        $this->setError('Resource \'iDatabase->' . $resource . '\' could not be freed.');

        return false;
      }
    }

    /**
     * nextID
     *
     * Get the ID last record
     *
     * @return  Get the ID generated from the previous INSERT operation otherwise false
     *
     * @access public
     */
    public function nextID() {
      if (is_numeric($this->nextID)) {
        $id = $this->nextID;
        $this->nextID = null;
        return $id;
      } elseif ($id = @mysql_insert_id($this->link)) {
        return $id;
      } else {
        $this->setError(mysql_error($this->link), mysql_errno($this->link));
        return false;
      }
    }

    /**
     * numberOfRows
     *
     * Get number of rows in result
     *
     * @param object $resource - The received descriptor
     *
     * @return The number of rows in a result set on success, or FALSE on failure.
     *
     * @access public
     */
    public function numberOfRows($resource) {
      return @mysql_num_rows($resource);
    }

    /**
     * affectedRows
     *
     * Get number of affected rows in previous MySQL operation
     *
     * @return  Returns the number of affected rows on success, and -1 if the last query failed.
     *
     * @access public
     */
    public function affectedRows() {
      return mysql_affected_rows($this->link);
    }

    /**
    * getStatus
    *
    * Get MySQL status info
    *
    * @return assoc array with status info
    *
    * @access public
    */
    public function getStatus(){
      $result = array('db_thread'        => '',
                      'db_protocol'      => mysql_get_proto_info($this->link) ,
                      'db_connection'    => mysql_get_host_info($this->link) ,
                      'db_char_conn'     => '',
                      'db_char_database' => '',
                      'db_char_client'   => '',
                      'db_char_server'   => '',
                      'db_port'          => '',
                      'db_questions'     => '',
                      'db_slow_query'    => '',
                      'db_opens'         => '',
                      'db_flush_tables'  => '',
                      'db_open_tables'   => '',
                      'db_query_per_sec' => '',
                      'db_version'       => '',
                      'db_user'          =>'');
      $temp = mysql_stat($this->link);
      $temp = explode('  ',$temp);
      $result['db_thread']        = array_pop(explode(':',$temp[1]));
      $result['db_questions']     = array_pop(explode(':',$temp[2]));
      $result['db_slow_query']    = array_pop(explode(':',$temp[3]));
      $result['db_opens']         = array_pop(explode(':',$temp[4]));
      $result['db_flush_tables']  = array_pop(explode(':',$temp[5]));
      $result['db_open_tables']   = array_pop(explode(':',$temp[6]));
      $result['db_query_per_sec'] = array_pop(explode(':',$temp[7]));
      $result['db_version']       = mysqli_get_server_info($this->link);
      $result['db_char_conn']     = $this->query('SHOW variables LIKE "character_set_connection"')->value('Value');
      $result['db_char_database'] = $this->query('SHOW variables LIKE "character_set_database"')->value('Value');
      $result['db_char_client']   = $this->query('SHOW variables LIKE "character_set_client"')->value('Value');
      $result['db_char_server']   = $this->query('SHOW variables LIKE "character_set_server"')->value('Value');
      $result['db_port']          = $this->query('SHOW variables LIKE "port"')->valueInt('Value');
      $result['db_user']          = $this->query('SELECT CURRENT_USER AS user')->value('user');
      return $result;
    }

    /**
     * startTransaction
     *
     * Start transaction
     *
     * @return In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function startTransaction() {
      $this->logging_transaction = true;

      if ($this->use_transactions === true) {
        return $this->simpleQuery('start transaction');
      }

      return false;
    }

    /**
     * commitTransaction
     *
     * commit transation
     *
     * @return  In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function commitTransaction() {
      if ($this->logging_transaction === true) {
        $this->logging_transaction = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        return $this->simpleQuery('commit');
      }

      return false;
    }

    /**
     * rollbackTransaction
     *
     * Refusal of all changes in the transaction, made after last fixing or a point of preservation
     *
     * @return  In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function rollbackTransaction() {
      if ($this->logging_transaction === true) {
        $this->logging_transaction = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        return $this->simpleQuery('rollback');
      }

      return false;
    }

    /**
     * isStartedTransaction
     *
     * @return true - if transaction started, otherwise false
     *
     * @access public
     */
    public function isStartedTransaction() {
      return $this->logging_transaction;
    }

    /**
     * setBatchLimit
     *
     * The method adds a limit to a line of sql query
     *
     * @param string $sql_query - sql query
     * @param int $from - With what element to begin
     * @param int $maximum_rows - quantity of taken elements
     *
     * @return the generated query
     *
     * @access public
     */
    public function setBatchLimit($sql_query, $from, $maximum_rows) {
      return $sql_query . ' LIMIT ' . $from . ', ' . $maximum_rows;
    }

    /**
     * getBatchSize
     *
     * Receives quantity of records as a result of inquiry
     *
     * @param string $sql_query - sql query
     * @param string $select_field - field name
     *
     * @return quantity of records as a result of inquiry
     *
     * @access public
     */
    public function getBatchSize($sql_query, $select_field = '*') {
      if (stripos($sql_query, 'SQL_CALC_FOUND_ROWS') !== false) {

        $bb = $this->query('SELECT found_rows() AS total');
      } else {
        $total_query = substr($sql_query, 0, stripos($sql_query, ' LIMIT '));
        $pos_to      = strlen($total_query);
        $pos_from    = stripos($total_query, ' FROM ');

        if (($pos_group_by = stripos($total_query, ' GROUP BY ', $pos_from)) !== false) {
          if ($pos_group_by < $pos_to) {
            $pos_to = $pos_group_by;
          }
        }

        if (($pos_having = stripos($total_query, ' HAVING ', $pos_from)) !== false) {
          if ($pos_having < $pos_to) {
            $pos_to = $pos_having;
          }
        }

        if (($pos_order_by = stripos($total_query, ' ORDER BY ', $pos_from)) !== false) {
          if ($pos_order_by < $pos_to) {
            $pos_to = $pos_order_by;
          }
        }

        $bb = $this->query('SELECT count(' . $select_field . ') AS total ' . substr($total_query, $pos_from, ($pos_to - $pos_from)));
      }

      return $bb->value('total');
    }

    /**
     * prepareSearch
     *
     * Creation of a line for search
     *
     * @param string $columns - where to carry out search
     *
     * @return line for search
     *
     * @access public
     */
    public function prepareSearch($columns) {
      if ($this->use_fulltext === true) {
        return 'match (' . implode(', ', $columns) . ') against (:keywords' . (($this->use_fulltext_boolean === true) ? ' in boolean mode' : '') . ')';
      } else {
        $search_sql = '(';

        foreach ($columns as $column) {
          $search_sql .= $column . ' LIKE :keyword or ';
        }

        $search_sql = substr($search_sql, 0, -4) . ')';

        return $search_sql;
      }
    }

    /**
     * query
     *
     * The announcement of new object class iDatabaseResult and fill field $sql_query
     *
     * @param string $query - sql query
     *
     * @return new object iDatabaseResult
     *
     * @access public
     */
    public function &query($query) {

      // Database Result
      $iDatabaseResult = new iDatabase_Result($this);
      $iDatabaseResult->setQuery($query);

      return $iDatabaseResult;
    }

  }
?>
