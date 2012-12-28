<?php
/**
 * Initialize core files for work
 * 
 * @author Konstantin Zhirnov
 * @todo добавить исключения для папок, файлов и расширений
 */
class coreLoader {
	
	private static $basePath;
	private static $excludeDirs = array();
	private static $excludeFiles = array();
	private static $excludeExtensions = array();

	/**
	 * Initialize autoloader
	 * 
	 * @acces public
	 * @static
	 * @param string $path path for search files
	 */
	public static function Init($path = '') {		
		if(!$path) {
			$path = dirname(__FILE__);
		}
		self::$basePath = $path;
		spl_autoload_register(array('coreLoader', 'loader'));
	}
	
	public static function AddExcludeDirs($excluded) {
		self::arrayFromString($excluded, 'excludeDirs');
	}
	
	public static function AddExcludeFiles($excluded) {
		self::arrayFromString($excluded, 'excludeFiles');
	}
	
	public static function AddExcludeExtensions($excluded) {
		self::arrayFromString($excluded, 'excludeExtensions');
	}
	
	private static function arrayFromString($string, $propertyName, $separator = ',') {
		$array = explode($separator, $string);
		
		foreach($array as $key => $value) {
			$array[$key] = trim($value);
		}
		
		if(is_array($array)) {
			self::$propertyName =array_merge(self::$propertyName, $array);
		} else {
			self::$propertyName->push($array);
		}
	}
	
	
	private static function loader($name) {
		$path = self::fileSearch(self::$basePath, $name);
		if(file_exists($path)){
			require_once $path;
		}
	}
	
	private static function fileSearch($path, $className) {
		$handle = opendir($path);
		$result = '';
		while(false !== ($name = readdir($handle))) {
			if ($name != "." && $name != "..") {
				$newPath = $path . DIRECTORY_SEPARATOR . $name;
				if(is_dir($newPath) && !in_array($name, self::$excludeDirs)) {
					$result = self::fileSearch($newPath, $className);
					if($result) { break; }
				} else {
					if( !in_array(pathinfo($newPath, PATHINFO_EXTENSION), self::$excludeExtensions) && !in_array($name, self::$excludeFiles) && $className ==  pathinfo($newPath, PATHINFO_FILENAME)) {
						return $newPath;
					}
				}
			}
		}
		closedir($handle);
		return $result;
	}
}
?>
