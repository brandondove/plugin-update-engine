<?php
/**
 * XMLRPC Controller
 *
 * @package Plugin Update Engine
 * @author Pixel Jar
 * @blatantly_ripped_off_from Blair Williams
 */
class pue_xmlrpc_controller {
	
	function __construct() {
		add_filter( 'xmlrpc_methods', array( &$this, 'xmlrpc_methods' ) );
	}

	/*
	 * Adding XMLRPC methods for the client plugin to call
	/**/
	function xmlrpc_methods( $api_methods ) {
		$api_methods['puengine.is_user_authorized']				= array( &$this, 'is_user_authorized' );
		/*
		$api_methods['puengine.is_user_allowed_to_download']	= array( &$this, 'is_user_allowed_to_download' );
		$api_methods['puengine.get_download_url']				= array( &$this, 'get_download_url' );
		$api_methods['puengine.get_encoded_download_url']		= array( &$this, 'get_encoded_download_url' );
		/**/

		return $api_methods;
	}
	
	function is_user_authorized( $args = array() ) {
		
		$defaults = array(
			'username'	=> false,
			'password'	=> false,
			'plugin'	=> false,
			'url'		=> false,
		);
		extract( wp_parse_args( $args, $defaults ), EXTR_OVERWRITE );
		
		if( !$username || !$password || !$plugin || !$url ) :
			global $pue_crazy_responses;
			return new IXR_Error( 401, $pue_crazy_responses[array_rand( $pue_crazy_responses )] );
		endif;
					
		// Strip the plugin slug down from foldername/mainfile.php to just mainfile
		$plugin = basename( $plugin, '.php' );

		// Username/password combo were not recognized
		if( !user_pass_ok( $username, $password ) ) :
			return new IXR_Error( 401, __( 'Sorry, Username and/or Password is Incorrect', 'plugin-update-engine' ) );
		endif;
		
		// It's a real user, populate the user object so we can test for access
		require_once( PUENGINE_CORE.'models/pue-authenticated-user.php' );
		$user = new pue_authenticated_user( $username );

		// User is not authorized for the requested plugin
		if( !$user->is_authorized( $plugin ) )
			return new IXR_Error( 401, __( "It appears that you don't have an active license for this plugin.", 'plugin-update-engine' ) );
		
		// User can't download for the requested domain 
		if( !$user->has_available_licenses( $plugin, $url ) )
			return new IXR_Error( 401, __( 'This domain has not been authorized because you have used up all of your licenses.', 'plugin-update-engine' ) );
		
		return md5( $username.$password.$plugin.$url.PUENGINE_SALT );
	}
}
new pue_xmlrpc_controller;