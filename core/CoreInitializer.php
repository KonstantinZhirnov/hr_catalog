<?php
/**
 * Initialize core files for work
 * 
 * @author Konstantin Zhirnov
 * @todo добавить исключения для папок, файлов и расширений
 */
class CoreInitializer {
	
	private $basePath;
	private $excludeDirs = array();
	private $excludeFiles = array();
	private $excludeExtensions = array();

	/**
	 * Initialize autoloader
	 * 
	 * @acces public
	 * @static
	 * @param string $path path for search files
	 */
	public function Init($path = '') {		
		if(!$path) {
			$path = dirname(__FILE__);
		}
		$this->basePath = $path;
		spl_autoload_register(array($this, 'loader'));
	}
	
	public function AddExcludeDirs($excluded) {
		$this->arrayFromString($excluded, 'excludeDirs');
	}
	
	public function AddExcludeFiles($excluded) {
		$this->arrayFromString($excluded, 'excludeFiles');
	}
	
	public function AddExcludeExtensions($excluded) {
		$this->arrayFromString($excluded, 'excludeExtensions');
	}
	
	private function arrayFromString($string, $propertyName, $separator = ',') {
		$array = explode($separator, $string);
		
		foreach($array as $key => $value) {
			$array[$key] = trim($value);
		}
		
		if(is_array($array)) {
			$this->$propertyName =array_merge($this->$propertyName, $array);
		} else {
			$this->$propertyName->push($array);
		}
	}
	
	
	private function loader($name) {
		$path = $this->fileSearch($this->basePath, $name);
		if(file_exists($path)){
			require_once $path;
		}
	}
	
	private function fileSearch($path, $className) {
		$handle = opendir($path);
		$result = '';
		while(false !== ($name = readdir($handle))) {
			if ($name != "." && $name != "..") {
				$newPath = $path . DIRECTORY_SEPARATOR . $name;
				if(is_dir($newPath) && !in_array($name, $this->excludeDirs)) {
					$result = $this->fileSearch($newPath, $className);
					if($result) { break; }
				} else {
					if( !in_array(pathinfo($newPath, PATHINFO_EXTENSION), $this->excludeExtensions) && !in_array($name, $this->excludeFiles) && $className ==  pathinfo($newPath, PATHINFO_FILENAME)) {
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
