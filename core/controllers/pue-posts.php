<?php

/*
 * Checks to see if there is a remote install calling home
/**/
class pue_post_controller {
	
	function __construct() {
		add_filter( 'query_vars', array( &$this, 'query_vars' ) );
		add_action( 'wp', array( &$this, 'wp' ), 1 );
	}
	
	function query_vars( $vars = array() ) {
		$vars[] = 'pue-plugin-information';
		$vars[] = 'pue-update-check';
		$vars[] = 'pue-download';
		return $vars;
	}
	
	function wp( $wp ) {
	
		
		// if the request didn't provide a plugin to check, just die
		if( ! isset( $_POST['plugin_name'] ) ) die();
			
		// Strip the plugin slug down from foldername/mainfile.php to just mainfile	
		$plugin = basename( $_POST['plugin_name'], '.php' );
		
		// calling home for update details
		if( array_key_exists( 'pue-plugin-information', $wp->query_vars ) && $wp->query_vars['pue-plugin-information'] == 'give-me-the-411' ) :
		
			if( isset( $_POST['action'] ) && $_POST['action'] == 'plugin_information' ) :
					
					require_once( PUENGINE_CORE.'models/pue-plugin-information.php' );
					$pue_plugin_information = new pue_plugin_information( $plugin );
					echo serialize( (object)$pue_plugin_information->response );
					
					die();
			endif;
		
		// calling home to see if there's an update
		elseif( array_key_exists( 'pue-update-check', $wp->query_vars ) && $wp->query_vars['pue-update-check'] == 'whats-new' ) :
			
			if( isset( $_POST['action'] ) && $_POST['action'] == 'update-check' ) :
			
				require_once( PUENGINE_CORE.'models/pue-update-check.php' );
				$pue_update_check = new pue_update_check( $plugin );
				if( version_compare( $pue_update_check->response['new_version'], $_POST['version'], '>' ) )
					echo serialize( (object)$pue_update_check->response );
					
				die();
				
			endif;
		
		// calling home for the download
		elseif( array_key_exists( 'pue-download', $wp->query_vars ) && !empty( $wp->query_vars['pue-download'] ) ) :
			
			// $wp->query_vars['pue-download'] is the version number requested
			if( isset( $_POST['action'] ) && $_POST['action'] == 'update-check' ) :
				require_once( PUENGINE_CORE.'models/pue-update-check.php' );
				die();
			endif;
			
		endif;
	}
}
new pue_post_controller;