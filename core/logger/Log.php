<?php

/**
 * Loging data to file or print it to the screen
 *
 * @author Konstantin Zhirnov
 */
class Log {
	
	/**
	 * Save data to file 
	 * 
	 * @access public
	 * @static
	 * @param object $log_data  data which will be saved
	 * @param string $comment comment to data 
	 * @param string $fname filename to save data
	 * @param bool $rewrite is file need be cleared
	 */
	public static function Save($log_data = null, $rewrite = false, $comment = false, $fname = '') {
		mkdir(dirname(__FILE__) . '/../../logs');
		$fh  = fopen(dirname(__FILE__) . '/../../logs/!_' . (($fname == '') ? 'log' : $fname) . '.log', ($rewrite === true) ? "w+": "a+");
		fwrite($fh, date('[D M d H:i:s Y]'));
		if ($comment) {
			fwrite($fh, " $comment\n");
		}
		fwrite($fh, var_export($log_data, true) . "\n");
		fclose($fh);
    }
	
	/**
	 * Save callstack to the debug.log
	 *
	 * @access public
	 * @static 
	 * @param bool $die is ned die after save data
	 */
    public static function Callstack($die = false) {
		try{
			$ex_text = is_string($die)?$die:'Log::Debug';
			throw new Exception($ex_text);
		} catch (Exception $e){
			Log::Save($e->__toString(), false, 'debug', true);
		}
		if ($die === true) die();
    }
	
	/**
	 * Save info about class to the file
	 * 
	 * @access public
	 * @static
	 * @param object $object object about which need information 
	 */
	public static function ClassInfo($object){
		Log::Save(get_class_vars(get_class($object)), 'Variables', get_class($object), true);
		Log::Save(get_class_methods(get_class($object)), 'Methods', get_class($object), true);
	}
	
	/**
	 * Print data to the screen
	 * 
	 * @access public
	 * @static
	 * @param object $data data which will be printed
	 * @param bool $isFullInfo is type of data needed
	 * @param string $comment comment for printing
	 * @param bool $die is die ater print
	 */
	public static function Show($data, $isFullInfo = false, $comment = '', $die = false) {
		$comment = $comment != '' ? $comment . '<hr />' : '';
		echo '<div style="font-size:14px; background-color:#FFFFFF; border:1px solid silver; padding:0 0 0 0; margin: 0 0 0 0;"><PRE>';
		echo $comment;

		$printData = is_string($data) ? htmlentities($data) : $data;
		if($isFullInfo){
			var_dump($printData);
		} else {
			print_r($printData);
		}

		echo '</PRE></div><br/>';

		if ($die === true) {
			die();
		}
	}
}

?>
