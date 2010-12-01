<?php
/**
 * @package NIB_WordPress_Menu.class.php
 */

/**
 * NIB_WordPress_Menu
 *
 * Used for adding new menus to the WordPress Administration Panel.
 * Could you do this with WordPress's procedural functions? You bet,
 * but this is easier and it helps maintain the menu hierarchy a little.
 * @author Dave
 *
 */
class NIB_WordPress_Menu	{
	protected $_title;
	protected $_url;
	protected $_capability;
	protected $_image;
	protected $_position;

	protected $_children;

	/**
	 * Creates a new top-level menu item.
	 *
	 * @param string $title The title for the link and the menu.
	 * @param string $url URL to link to.  Links should be relative to wp-admin/
	 * @param string $capability The capability required for this link. Default: manage_options
	 * @param string $image Absolute URL to the icon image.  Default: none.
	 * @param int $position Menu position.
	 */
	public function __construct($title,$url,$capability='manage_options',$image=null,$position=null)	{
		$this->_children = array();

		$this->_title = $title;
		$this->_url = $url;
		$this->_capability = $capability;
		$this->_image = $image;
		$this->_position = $position;

		add_action('admin_init',array(&$this,'register'));
	}

	/**
	 * Adds a submenu item to this menu.
	 * @param string $title Title for this submenu item
	 * @param string $url URL for this link. Links should be relative to wp-admin/
	 * @param string $capability The capability required for this page. Default: parent capability
	 */
	public function addSubmenu($title,$url,$capability=null)	{
		if(is_null($capability))	{ $capability = $this->_capability; }
		$this->_children[] = array('title'=>$title,'url'=>$url,'capability'=>$capability);
	}

	/**
	 * Function exists to delay menu registration until such point as the necessary functions have been loaded.
	 */
	public function register()	{
		add_menu_page($this->_title, $this->_title, $this->_capability, $this->_url, null, $this->_image, $this->_position);
		foreach($this->_children as $child)	{
			add_submenu_page($this->_url,$child['title'],$child['title'],$child['capability'],$child['url']);
		}
	}
}