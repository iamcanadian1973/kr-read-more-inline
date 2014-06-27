<?php
/**
 * KR Read More Inline
 *
 * @package      kr-read-more-inline
 * @since        1.0
 * @link         https://github.com/kylerumble/kr-read-more-inline
 * @author       Kyle Rumble <kyle@kylerumble.com>
 * @copyright    Copyright (c) 2014, Kyle Rumble
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */



class KR_Read_More_Inline
{
	/**
	 * Construct method.  Adds the class methods to hooks in WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );			

		// Disable plugin updates
		//add_filter( 'http_request_args', array( $this, 'dm_prevent_update_check'), 10, 2 );	
		
		// Suppress plugin update notice
		add_filter('site_transient_update_plugins', array( $this, 'remove_plugin_update_notice' ) );
		
		
		add_action( 'init', array( &$this, 'load_scripts') );
	}
	
	
	/**
	 * Activation Hook - Confirm site is using Genesis
	 *
	 */

	public function activation_hook() {

		if ( 'genesis' != basename( TEMPLATEPATH ) ) {

			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die( sprintf( __( 'Sorry, you can&rsquo;t activate unless you have installed <a href="%s">Genesis</a>', 'genesis-simple-settings'), 'http://my.studiopress.com/themes/genesis/' ) );

		}						

	}

	
	/**
	 * Remove plugin update notice
	 *
	*/
	
	public function remove_plugin_update_notice($value) {
		if ( isset( $value ) && is_object( $value ) )
	 		unset( $value->response[plugin_basename(__FILE__)] );
	 	return $value;
	}
	
	
	/**
	 * Load Scripts
	 *
	*/
	public function load_scripts() {

		wp_register_script('kr-read-more-inline', plugins_url( 'lib/js/plugin.js', __FILE__ ), array('jquery' ), '', TRUE);
		
		wp_enqueue_script('kr-read-more-inline');
		
		wp_enqueue_style( 'kr-read-more-inline', plugins_url( 'lib/css/style.css', __FILE__) );

	}
	
}


new KR_Read_More_Inline();