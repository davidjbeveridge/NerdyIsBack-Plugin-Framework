<?php
/**
 * @package NIB_CompositeView.class.php
 */

/**
 * NIB_CompositeView class
 *
 * Extends Views to allow for children and composite-style display;
 * calling render() on any level of the hierarchy should result in a
 * top-down display from that point in the tree.
 *
 * @package NIB_CompositeView
 * @see NIB_View
 *
 */
class NIB_CompositeView extends NIB_View	{

	protected $_children = array();

	/**
	 * Appends a child.
	 *
	 * @param NIB_View $child
	 */
	public function addChild($child)	{
		if($child instanceof NIB_View)	{
			$this->_children[] = $child;
		}
	}

	/**
	 * Renders the children.
	 *
	 * This function should be called inside the template file used by
	 * the NIB_CompositeView instance. For example, div.php (a template file)
	 * might contain the following code:
	 *
	 * <div>
	 * <?php $this->renderChildren(); ?>
	 * </div>
	 */
	protected function renderChildren()	{
		foreach($this->_children as $child)	{
			$child->render(TRUE);
		}
	}
}