<?php

/**
 * Description of ErrorModule
 *
 * @author Konstantin Zhirnov
 */
class ErrorModule implements IModule {
	public function Run() {
		error_reporting(E_ALL | E_NOTICE);
		ini_set('display_errors', 'Off');
		
		set_error_handler(array ($this, "handleError"), $errorLevel);
	}
	
	public function handleError($errno, $msg, $file, $line) {
		Log::save(array($msg, debug_backtrace()), false, "{$this->_getErrorName($errno)} : $file -> $line");
		
		$this->_handleError($this->_getErrorName($errno), $msg, $file, $line, debug_backtrace());
	}

	protected function _getErrorName($errno) {
		switch ($errno) {
			case E_ERROR :
				{
					return "E_ERROR";
				}
			case E_WARNING :
				{
					return "E_WARNING";
				}
			case E_PARSE :
				{
					return "E_PARSE";
				}
			case E_NOTICE :
				{
					return "E_NOTICE";
				}
			case E_CORE_ERROR :
				{
					return "E_CORE_ERROR";
				}
			case E_CORE_WARNING :
				{
					return "E_CORE_WARNING";
				}
			case E_COMPILE_ERROR :
				{
					return "E_COMPILE_ERROR";
				}
			case E_COMPILE_WARNING :
				{
					return "E_COMPILE_WARNING";
				}
			case E_USER_ERROR :
				{
					return "E_USER_ERROR";
				}
			case E_USER_WARNING :
				{
					return "E_USER_WARNING";
				}
			case E_USER_NOTICE :
				{
					return "E_USER_NOTICE";
				}
			case E_RECOVERABLE_ERROR :
				{
					return "E_RECOVERABLE_ERROR";
				}
			case E_STRICT :
				{
					return "E_STRICT";
				}
			default :
				{
					return "#" . $errno;
				}
		}
	}
}

?>
