<?php

/**
 * Differrent functions for using in project
 *
 * @author Konstantin Zhirnov
 */
class Helper {
  /**
   * Generate new GUID
   * @param bool $isEmpty is empty GUID will be created
   * @return string string with format {XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX}
   */
  public static function getGUID($isEmpty = false) {
    $result = null;
    if($isEmpty) {
      $result = "{00000000-0000-0000-0000-000000000000}";
    } else {
      $result = sprintf( '{%04x%04x-%04x-%04x-%04x-%04x%04x%04x}',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,
        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
      );
    }
    return $result;
  }
  
  /**
   * retrieve MD5 hash of data
   * @param string $data string for hash creating
   * @return string
   */
  public static function getMd5Hash($data) {
    return md5($data);
  }
  
  /**
   * check is string start with defined chars
   * @param string $haystack string for search
   * @param string $needle searched characters
   * @return bool true if string start with needle characters
   */
  public static function startsWith($haystack, $needle)
  {
    return !strncmp(strtolower($haystack), strtolower($needle), strlen($needle));
  }

  /**
   * check is string end with defined chars
   * @param string $haystack string for search
   * @param string $needle searched characters
   * @return bool true if string end with needle characters
   */
  public static function endsWith($haystack, $needle)
  {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr(strtolower($haystack), -$length) === strtolower($needle));
  }
  
  /**
   * Add conditions to specified SQL query
   * @param iDtabase_Result $dbResult
   * @param array $conditions
   */
  public static function addSqlConditions (&$dbResult, $conditions) {
    if($dbResult && $conditions && is_array($conditions)) {
      $separator = ',';
      
      if(Helper::startsWith($dbResult->sql_query, 'select')) {
        $separator = ' and';
      }
      
      end($conditions);
      $lastElementKey = key($conditions);
      foreach($conditions as $key => $value) {
        if($key == $lastElementKey) {
          $separator = '';
        }
        
        $condition = "=";
        if(is_array($value)) {
          if(isset($value['condition'])) {
            $condition = $value['condition'];
          }
          $value = $value['value'];
        }
        
        $dbResult->appendQuery(" `{$key}` {$condition} :{$key}{$separator}");
        $dbResult->bindValue(":{$key}", $value);
      }
    }
  }
}

?>
