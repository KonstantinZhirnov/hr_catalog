<?php
/**
 * File iDatabase_mysqli.php
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
   * iDatabase_mysqli
   *
   * The iDatabase_mysqli class contains methods for work with a database
   *
   * @copyright Copyright (c) 2010, iLogos
   *
   * @see iDatabase_mysql
   * @see iISingleton
   */
  class iDatabase_mysqli extends iDatabase_mysql implements ISingleton  {

   /**
    * contains object of a class
    * @var object
    * @access  private
    * @static
    */
    private static $_instance = null;

    /**
     * use transactions
     * @var boolean
     * @access public
     */
    public $use_transactions = true;

    /**
     * __construct
     * construct class
     *
     * @access protected
     */
    protected function __construct() {
    }

    /**
     * getInstance
     *
     * Method for reception of object of a class
     *
     * 
     * @return iDatabase_mysqli Description
     * @access public
     */
    public static function getInstance($param = false) {
      if(!self::$_instance){
        self::$_instance = new iDatabase_mysqli();
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
      if ($this->is_connected === false) {
        if ($this->link = @mysqli_connect($this->server, $this->username, $this->password)) {
          $this->setConnected(true);
          mysqli_query($this->link, "SET NAMES 'utf8'");
          return true;
        } else {
          $this->setError(mysqli_connect_error(), mysqli_connect_errno());
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
      $id = intval(mysqli_insert_id($this->link));
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
     * @return  true - In case of success otherwise false
     *
     * @access public
     */
    public function disconnect() {
      if ($this->isConnected()) {
        if (@mysqli_close($this->link)) {
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
     * @return  true - In case of success otherwise false
     *
     * @access public
     */
    public function selectDatabase($database) {
      if ($this->isConnected()) {
        if (@mysqli_select_db($this->link, $database)) {
          return true;
        } else {
          $this->setError(mysqli_error($this->link), mysqli_errno($this->link));
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
    public function parseString($value) {
      return mysqli_real_escape_string($this->link, $value);
    }

    /**
     * simpleQuery
     *
     * Send a sql query
     *
     * @param string $query - sql query
     * @param boolean $debug - whether query should be debugged
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

      if (defined('DEBUG_OUTPUT_DB_QUERIES') && DEBUG_OUTPUT_DB_QUERIES == 1 || $debug) {
        $debug = true;
  
        ++$this->number_of_queries;
        $query_start_time = microtime(1);
      }

      $resource = @mysqli_query($this->link, $query);

      if ($resource === false) {
        //if (defined('DEBUG_SHOW_ERROR_QUERY') && DEBUG_SHOW_ERROR_QUERY == 1) {
          $this->setError(mysqli_error($this->link), mysqli_errno($this->link), $query);
        //}
      } else {
        $this->error        = false;
        $this->error_number = null;
        $this->error_query  = null;

        //$this->nextID = $this->getLastInsertId();
      }

      if ($debug) {
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
     * @param int $row_number - The result resource that is being evaluated. This result comes from a call to mysqli_query().
     * @param object $resource - The desired row number of the new result pointer.
     *
     * @return Returns TRUE on success or FALSE on failure.
     *
     * @access public
     */
    public function dataSeek($row_number, $resource) {
      return @mysqli_data_seek($resource, $row_number);
    }

    /**
     * next
     *
     * Extraction of data received from database
     *
     * @param object $resource - The received descriptor
     *
     * @return  Returns an associative file with names of indexes, those are according to names of columns or FALSE if numbers are not present more.
     *
     * @access public
     */
    public function next($resource) {
      return @mysqli_fetch_assoc($resource);
    }

    /**
     * freeResult
     *
     * Free result memory
     *
     * @param object $resource - The result resource that is being evaluated. This result comes from a call to mysqli_query().
     *
     * @return Returns TRUE on success or FALSE on failure.
     *
     * @access public
     */
    public function freeResult($resource) {
      return @mysqli_free_result($resource);
    }

    /**
     * nextID
     *
     * Get the ID last record
     *
     * @return Get the ID generated from the previous INSERT operation otherwise false
     *
     * @access public
     */
    public function nextID() {
      if (is_numeric($this->nextID)) {
        $id = $this->nextID;
        $this->nextID = null;

        return $id;
      } elseif ($id = @mysqli_insert_id($this->link)) {
        return $id;
      } else {
        $this->setError(mysqli_error($this->link), mysqli_errno($this->link));

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
      return @mysqli_num_rows($resource);
    }

    /**
     * affectedRows
     *
     * Get number of affected rows in previous MySQL operation
     *
     * @return Returns the number of affected rows on success, and -1 if the last query failed.
     *
     * @access public
     */
    public function affectedRows() {
      return @mysqli_affected_rows($this->link);
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
        return @mysqli_autocommit($this->link, false);
      }

      return false;
    }

    /**
     * commitTransaction
     *
     * commit transaction
     *
     * @return  In case of success returns a resource, otherwise false
     *
     * @access public
     */
    public function commitTransaction() {
      if ($this->logging_transaction === true) {
        $this->logging_transaction        = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        $result = @mysqli_commit($this->link);
        @mysqli_autocommit($this->link, true);
        return $result;
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
        $this->logging_transaction        = false;
        $this->logging_transaction_action = false;
      }

      if ($this->use_transactions === true) {
        $result = @mysqli_rollback($this->link);
        @mysqli_autocommit($this->link, true);
        return $result;
      }

      return false;
    }

    /**
     * query
     *
     * The announcement of new object class iDatabase_Result and fill field $sql_query
     *
     * @param string $query - sql query
     *
     * @return iDatabase_Result - new object iDatabase_Result
     *
     * @access public
     */
    public function &query($query) {

      // Database result
      $iDatabase_Result = new iDatabase_Result($this);
      $iDatabase_Result->setQuery($query);

      return $iDatabase_Result;
    }

    /**
     * query
     *
     * The announcement of new object class iDatabase_Result and fill field $sql_query
     *
     * @param string $query - sql query
     * @param int $expireTime - expire time
     *
     * @return iDatabase_Result - new object iDatabase_Result
     *
     * @access public
     */
    public function &cacheQuery($query, $expireTime = DB_EXPIRY_TIME) {

      // Database result
      $iDatabase_Result = new iDatabase_Result($this);
      $iDatabase_Result->setQuery($query);

      if (!defined('IN_CRON')) {
        $iDatabase_Result->setFileCacheUse(true, $expireTime);
      }

      return $iDatabase_Result;
    }

    /**
     * Get MySQL status info
     * @return assoc array with status info
     */
    public function getStatus(){
      $result = array('db_thread'        => '',
                      'db_protocol'      => mysqli_get_proto_info($this->link) ,
                      'db_connection'    => mysqli_get_host_info($this->link) ,
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
      $temp = mysqli_stat($this->link);
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
  }
?>
