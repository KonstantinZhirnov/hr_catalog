<?php
/**
 * class for add and run modules which will be working on the site
 *
 * @author Konstantin Zhirnov
 */
class ModuleChain {
	
	/**
	 * @access private
	 * @var array IModule
	 */
	private $modules = array();
	
	/**
	 * Add module to the system
	 * 
	 * @access public
	 * @param IModule $module 
	 */
	public function AddModule(IModule $module) {
		$this->modules[] = $module;
	}
	
	/**
	 * Run each previously added module
	 * 
	 * @access public
	 */
	public function Process() {
		foreach($modules as $key => $module) {
			$module->Run();
		}
	}
}

?>
