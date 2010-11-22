<?php
/**
 * NIB_Plugin Class
 *
 * NIB_Plugin should be extended to create plugins compatible with the NerdyIsBack Plugin Framework.
 * It acts as an object factory (via the static NIB_Plugin::instance() method) for creating new plugin
 * objects.  NIB_Plugin cannot be instantiated, nor should its children be created without the use of
 * the NIB_Plugin::instance() method.
 *
 * @package NIB_Plugin
 * @author David Beveridge <davidjbeveridge@gmail.com>
 * @copyright 2010 David Beveridge
 * @license http://www.opensource.org/licenses/mit-license.php
 * @version: 0.1
 *
 */
abstract class NIB_Plugin	{

	protected static $_instances = array();
	protected $_dirname = '';
	protected $_upload_pathinfo;
	protected $_config;

	/**
	 * __construct() is abstract and protected to prevent direct instantiation.
	 */
	abstract protected function __construct();

	/**
	 * NIB_Plugin::instance() factory method
	 *
	 * NIB_Plugin::instance() should be used for creating and getting all instances of all child classes
	 * of NIB_Plugin.  This function instantiates the plugin and returns the instance.
	 *
	 * @param string $instance_id Unique identifier for this instance
	 * @param string $class Name of the class you wish to instantiate
	 * @param NIB_Config $config an instance of NIB_Config; must have the 'plugin_directory' directive set.
	 * @return NIB_Plugin
	 */
	public static final function instance($instance_id,$class=null,&$config=null)	{

		if(empty($instance_id))	{
			return null;
		}
		if(!isset(NIB_Plugin::$_instances[$instance_id]) && !is_null($class) && class_exists($class))	{
			if(is_null($config) || !is_a($config, 'NIB_Config'))	{
				return null;
			}
			NIB_Plugin::$_instances[$instance_id] = new $class;
			NIB_Plugin::$_instances[$instance_id]->setConfig($config);
			NIB_Plugin::$_instances[$instance_id]->setDirectoryName($config->directive('plugin_directory'));
		}
		return NIB_Plugin::$_instances[$instance_id];
	}

	/**
	 * Returns the local path to the plugin directory
	 *
	 * @return string
	 */
	public final function pluginDir()	{
		return $this->_upload_pathinfo['basedir'].'/'.$this->_dirname;
	}

	/**
	 * Returns the URL to the plugin directory
	 *
	 * @return string
	 */
	public final function pluginURL()	{
		return $this->_upload_pathinfo['baseurl'].'/'.$this->_dirname;
	}


	/**
	 * Returns the configuration object
	 *
	 * @return NIB_Config
	 */
	public function &getConfig()	{
		return $this->_config;
	}

	/**
	 * Sets the configuration object
	 *
	 * @param NIB_Config $config
	 */
	public function &setConfig($config)	{
		$this->_config = $config;
		$this->_upload_pathinfo = wp_upload_dir();
	}


	/**
	 * Sets the plugin's directory name.
	 *
	 * @param string $dirname
	 */
	protected function setDirectoryName($dirname)	{
		$this->_dirname = $dirname;
	}
}