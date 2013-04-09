<?php
/**
 * Class for cache data to memcache
 *
 * @author Konstantin Zhirnov
 */
class CacheMemcached implements ISingleton {
  /**
     * The field of a class contains object of a class
     * @access private
     */
    private static $_instance = null;

    /**
     * memcache object
     * @access protected
     */
    private $_memcache = false;

    /**
     * class constructor
     *
     * This function required for support fo singleton interface
     *
     * @access public
     **/
    protected function __construct() {
      if (class_exists('Memcache')) {
        $this->_memcache = new Memcache;
        

        //allow to cleare cached data database
        if (isset($_REQUEST['clear_cache'])) {
          if ($_REQUEST['clear_cache'] != '') {
            $this->clear($_REQUEST['clear_cache']);
          } else {
            $this->clear();
          }
        }
      } else {
        return false;
      }
    }
    
    public function connect($server, $port) {
      $this->_memcache->connect($server, $port, 3) or die ("Could not connect to Memcached");
    }

    /**
     * getInstance
     *
     * This function required for support fo singleton interface
     *
     * @access public
     * @return CacheMemcached instance of CaheMemcached
     **/

    public static function getInstance($param = false) {
      if(!self::$_instance) {
        self::$_instance = new CacheMemcached();
      }
      return self::$_instance;
    }

    /**
     * write
     *
     * This function is stores cached data
     *
     * @param  string  $instance_name  - The key used to store the value
     * @param  pointer $data           - The variable to store.
     *
     * @access public
     **/
    public function write($instance_name, &$data, $expires = 100) {

      if ( !(class_exists('Memcache')) ) {
        return false;
      }

      if (!empty($instance_name)) {
        return $this->_memcache->set($instance_name, $data, false, $expires);
      }

      return false;
    }

    /**
     * read
     *
     * This function is read
     *
     * @param string $instance_name - The key used to store the value
     *
     * @access public
     **/
    public function read($instance_name = '') {

      if ( !(class_exists('Memcache')) ) {
        return false;
      }

      return $this->_memcache->get($instance_name);
    }

    /**
     * clear
     *
     * This function clears cached data
     *
     * @var string | array $instance_name - The key used to store the value
     * @var bool           $use_like      - set this param in TRUE if you want delete cache when $instance_name is a part of key
     *
     * @access public
     **/

    public function clear($instance_name = '') {
      if ( !(class_exists('Memcache')) ) {
        return false;
      }

      $this->_memcache->delete($instance_name);
    }
}

?>
