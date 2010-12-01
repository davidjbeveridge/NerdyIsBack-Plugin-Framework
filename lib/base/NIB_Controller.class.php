<?php
/**
 * @package NIB_Controller.class.php
 */

/**
 * Controller base class
 *
 * Abstract Controller class should be extend in child projects to
 * create controllers.
 *
 * @package NIB_Controller
 *
 */
abstract class NIB_Controller	{

	protected $_views = array();
	protected $_models = array();
	protected $_parent;

	/**
	 * Instantiate controller and set $parent
	 *
	 * @param NIB_Plugin $parent The NIB_Plugin object creating the view.
	 */
	public function __construct($parent)	{
		$this->_parent = $parent;
	}

	/**
	 * Concatenates the output from all attached views and returns it.
	 */
	public function getOutput()	{
		$output = '';
		foreach($this->_views as $view)	{
			$output .= $view->render();
		}
		return $output;
	}

	/**
	 * Creates an instance of a model, or returns the existing instance, if present.
	 *
	 * @param string $name The name of the Model.  If you want to load MyPlugin_MyModel_Model, pass 'MyModel' for $name.
	 */
	protected function model($name){
		if(!isset($this->_models[$name]))	{
			$file = $this->_parent->pluginDir().'/model/'.$name.'.php';
			$class = $this->_parent->getPluginName().'_'.$name.'_Model';
			if(file_exists($file))	{
				include($file);
				if(class_exists($class))	{
					$this->_models[$name] = new $class;
				}
				else	{
					throw new NIB_Exception("Model $class could not be loaded: class $class not defined in $file");
				}
			}
			else	{
				throw new NIB_Exception("Model '$name' could not be loaded: file $file not found");
			}
		}
		if(isset($this->_models[$name]))	{
			return $this->_models[$name];
		}
		return null;
	}

	/**
	 * Creates a View object and returns it.
	 *
	 * @param string $path Name of the template file in the 'view/' directory.  Eg. you want to load view/user/profile.php, pass 'user/profile.php'
	 * @param array $data An associative array containing data to pass to the template file.  Keys should correspond to variable names used in the file.
	 * @param string $type The name of the class to be instantiated.  This class should be of type NIB_View (or a child class).  For composite views, pass 'NIB_CompositeView'
	 */
	protected function createView($path,$data=null,$type='NIB_View'){
		$path = $this->_parent->pluginDir().'/view/'.$path.'.php';
		$v = new $type($path,$data);
		return $v;
	}

	/**
	 * Attach a view object to the controller
	 *
	 * @param NIB_View $view
	 */
	protected function attachView($view)	{
		if($view instanceof NIB_View)	{
			$this->_views[] = $view;
		}
	}
	
	protected function index()	{
		throw new NIB_Exception('Default controller index method.  You should re-implement this in your child class.');
	}
}