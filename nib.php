<?php
require_once('admin.php');
if ( is_multisite() ) {
	$menu_perms = get_site_option( 'menu_items', array() );

	if ( empty($menu_perms['plugins']) && ! is_super_admin() )
		wp_die( __( 'Cheatin&#8217; uh?' ) );
}

if ( ! current_user_can( 'activate_plugins' ) )	{
	wp_die( __( 'You do not have sufficient permissions to manage plugins for this site.' ) );
}

$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : null;

if(isset($plugin) && !is_null(NIB_Plugin::instance($plugin)))	{
		NIB_Plugin::instance($plugin)->controller($action);
}
else	{
	wp_die( __("Page not found.") );
}
