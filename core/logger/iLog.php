<?php
/**
 * File iLog.php
 *
 * File containing the iLog class
 *
 * This file is part of Eldorado project
 *
 * PHP versions 5
 *
 * @copyright Copyright (c) 2010, iLogos
 */

  /**
   * iLog
   *
   * The iLog class provides logic of work with logs.
   *
   * @copyright Copyright (c) 2010, iLogos
   */
  class iLog {

    /**
     * array data of message types
     * @var array
     * @access private
     */
    private static $_message_types = array('message', 'warning', 'error');

    /**
     * add
     *
     * This function save of message by message type
     *
     * @param string $data - text of message
     * @param string $type - type of message
     * @param string $filename - filename
     * @param float $errorTime - error time
     * @param boolean $isFatal - true if fatal error
     *
     * @access public
     */
    public static function add($data, $type = 'message', $filename = null, $errorTime = null, $isFatal = false) {
      if (!in_array($type, self::$_message_types)) {
        $type = 'message';
      }

      if (defined('DEBUG_OUTPUT_' . strtoupper((($type == 'message')?'info':$type)) . '_MESSAGE_FILE') && constant('DEBUG_OUTPUT_' . strtoupper((($type == 'message')?'info':$type)) . '_MESSAGE_FILE')) {
        //save in file
        self::_saveToFile($data, $type, $filename);
      }
      if (!$isFatal && defined('DEBUG_OUTPUT_' . strtoupper((($type == 'message')?'info':$type)) . '_MESSAGE_DB') && constant('DEBUG_OUTPUT_' . strtoupper((($type == 'message')?'info':$type)) . '_MESSAGE_DB')) {
        if (is_null($errorTime)) {
          $errorTime = microtime_float();
        }
        //save db
        //self::_saveToDB($data, $type, $errorTime);
      }
    }

    /**
     * _saveToFile
     *
     * This function save of message in file
     *
     * @param string $data - text of message
     * @param string $type - type of message
     * @param string $filename - filename
     *
     * @access private
     */
    private static function _saveToFile($data, $type, $filename = null) {
      if (is_null($filename)) {
        $filename = $type;
      }
      $flog  = fopen(DIR_LOGS . ($filename . '.log'), "a+");
      fwrite($flog, date('[D M d H:i:s Y]') . " " . $data . "\n\n");
      fclose($flog);
    }

    /**
     * _saveToDB
     *
     * This function save of message in db
     *
     * @param string $data - text of message
     * @param string $type - type of message
     * @param float $errorTime - error time
     *
     * @access private
     */
    private static function _saveToDB($data, $type, $errorTime) {
      $iDatabase = iFactory::singleton('iDatabase_'.DB_DATABASE_CLASS);
      $iDatabase->checkLock(TABLE_LOG, 10);
      $Qinsert_message = $iDatabase->query('INSERT INTO :table_log (time, type, data) VALUES (:time, :type, :data)');
      $Qinsert_message->bindTable(':table_log', TABLE_LOG);
      $Qinsert_message->bindFloat(':time', $errorTime);
      $Qinsert_message->bindValue(':type', $type);
      $Qinsert_message->bindValue(':data', serialize($data));
      $Qinsert_message->execute();
    }

    /*
     CREATE TABLE `csLog` (
      `uid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `time` DECIMAL(20,8) NOT NULL,
      `type` ENUM('message', 'warning', 'error') NOT NULL DEFAULT 'message',
      `data` TEXT,
      KEY i_csLog_time (time),
      KEY i_csLog_type (type)
      ) ENGINE=INNODB DEFAULT CHARSET=utf8;

    */
  }
?>