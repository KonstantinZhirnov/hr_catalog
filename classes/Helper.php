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
}

?>
