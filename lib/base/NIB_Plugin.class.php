<?php
/**
 * @package NIB_Plugin.class.php
 */

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

	protected $_name;
	protected $_dirname = '';
	protected $_upload_pathinfo;
	protected $_controllers = array();


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
	 * @return NIB_Plugin
	 */
	public static final function instance($instance_id,$class=null,$directory=null)	{

		if(empty($instance_id))	{
			return null;
		}
		if(!isset(NIB_Plugin::$_instances[$instance_id]) && !is_null($class) && class_exists($class))	{
			NIB_Plugin::$_instances[$instance_id] = new $class;
			NIB_Plugin::$_instances[$instance_id]->setPluginName($instance_id);
			NIB_Plugin::$_instances[$instance_id]->setDirectoryName($directory);
		}
		if(@NIB_Plugin::$_instances[$instance_id])	{
			return NIB_Plugin::$_instances[$instance_id];
		}
		return null;
	}

	/**
	 * Returns the local path to the plugin directory
	 *
	 * @return string
	 */
	public final function pluginDir()	{
		return WP_PLUGIN_DIR.'/'.$this->_dirname;
	}

	/**
	 * Returns the URL to the plugin directory
	 *
	 * @return string
	 */
	public final function pluginURL()	{
		return WP_PLUGIN_URL.'/'.$this->_dirname;
	}


	/**
	 * Return Plugin Name;
	 */
	public function getPluginName()	{
		return $this->_name;
	}

	/**
	 * Returns application URL's
	 *
	 * NIB_Plugin::URL() can be used in two ways:
	 *
	 * $myPlugin->URL('Controller','method','arg_1', ... ,'arg_n')
	 * or
	 * $myPlugin->URL('Controller/method/arg1/arg2')
	 *
	 * Either will return somethingl like "http://path.to.wordpress/wp-admin/nib.php?plugin=MyPlugin&action=Controller/method/arg1/arg2"
	 *
	 * @param mixed $controller The controller name, or full path to controller method with arguments
	 * @param string $method The method name
	 * @param string $arg The argument(s) to be supplied to the controller method
	 * @return string $URL
	 */
	public function URL()	{
		$action = implode('/',func_get_args());
		return (WP_SITEURL."/wp-admin/nib.php?plugin=".$this->_name."&action=".$action);
	}

	/**
	 * Returns URL's relative to the wp-admin/ directory.
	 *
	 * Works the same way as NIB_Plugin::URL(), except the returned URL's will be relative to the wp-admin directory.
	 * For example, $myPlugin->adminURL('Controller','method','arg1) will return
	 * nib.php?plugin=MyPlugin&action=Controller/method/arg1
	 * @see NIB_Plugin::URL()
	 *
	 * @param mixed $controller The controller name, or full path to controller method with arguments
	 * @param string $method The method name
	 * @param string $arg The argument(s) to be supplied to the controller method
	 * @return string $URL
	 */
	public function adminURL()	{
		$args = func_get_args();
		$action = implode('/',$args);
		return ("nib.php?plugin=".$this->_name."&action=".$action);
	}

	/**
	 * Picks a strategy for handling a requested action via the application's controller(s).
	 *
	 * Parses the $action string, to determine the appropriate Controller/method and calls
	 * it, passing in any appropriate arguments.  Controllers are cached in the plugin instance
	 * upon instantiation.
	 *
	 * A few notes:
	 * The default controller is called 'Default'
	 * The default method is called 'index'
	 *
	 * If no Controller or method is specified, defaults will be used.
	 *
	 * @param string $action The action string to be used, in the format 'ControllerName/methodName/arg_1/.../arg_n'
	 */
	public function controller($action='')	{
		// Parse the request
		$c = null;	// Controller name
		$f = null;	// Method name
		$a = null;	// arguments

		$map = split('/',$action);
		@list($c,$f) = $map;
		if(count($map) > 2)	{
			$a = array_slice($map,2);
		}
		if(is_null($a))	{
			$a = array();
		}

		// If no controller is specified, use Default.
		if(!$c)	{
			$c = 'Default';
		}

		// Request parsed, determine appropriate course of action and execute.
		$cname = $this->_name.'_'.$c.'_Controller';			// Class Name of controller
		$cfile = $this->pluginDir()."/controller/$c.php";	// Path to file containing the controller's class

		if(file_exists($cfile))	{

			// Got the file, so include it.
			include_once($cfile);

			if(class_exists($cname))	{

				// Our controller's class is defined, so instantiate it.
				$this->_controllers[$c] = new $cname($this);

				// Do we have a method name?
				if($f)	{
					// Does that method actually exist?
					if(method_exists($this->_controllers[$c],$f))	{
						// Yes, so call it, passing our arguments
						@call_user_func_array(array($this->_controllers[$c],$f), $a);
						// Get the output from our controller, run it through a filter, and echo it.
						echo $this->filterOutput($this->_controllers[$c]->getOutput());
					}
					else	{
						throw new NIB_Exception("$c: Function '$f' not defined.");
					}
				}
				else	{
					// No method was requested, so we're using 'index' with no arguments
					call_user_func(array($this->_controllers[$c],'index'));
					// Get the output from our controller, run it through a filter, and echo it.
					echo $this->filterOutput($this->_controllers[$c]->getOutput());
				}
			}
			else	{
				throw new NIB_Exception('Controller not found.');
			}
		}
		else	{
			throw new NIB_Exception("Could not find file: $cfile");
		}
	}

	/**
	 * Filters output from controllers
	 *
	 * This method is in place to be overwritten.  The default
	 * implementation is to simply return the output, i.e., no
	 * actual filtering takes place.
	 *
	 * @param string $input
	 * @return string $input;
	 */
	protected function filterOutput($input)	{
		return $input;
	}

	/**
	 * Sets the plugin's directory name.
	 *
	 * @param string $dirname
	 */
	protected function setDirectoryName($dirname)	{
		$this->_dirname = $dirname;
	}

	/**
	 * Sets the name of this particular instance.  This name is used
	 * for a number of things, including:
	 * 	-Prefixing the class names of
	 * 		-controllers
	 * 		-models
	 * 	-Referencing this instance
	 *
	 * @param string $name The instance name
	 */
	protected function setPluginName($name)	{
		$this->_name = $name;
	}
}