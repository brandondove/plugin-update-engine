<?php

/**
 * Update Check Response
 *
 * @package Plugin Update Engine
 * @author Pixel Jar
 * @version 0.1
 **/
class pue_update_check {
	
	var $plugins;				// set in __construct()
	var $slug;					// set in __construct()
	
	var $new_version;			// set in plugin_head()
	var $url;					// set in plugin_head()
	
	var $package;				// set in plugin_package()
	var $response;				// attach everything to the response array
	
	function __construct( $slug ) {
		
		$this->slug = $slug;
		$this->response = array();
		
		// get all plugins managed by this plugin
		// talk about a swirling vortex of DOOM!
		$this->plugins = get_option( 'pue-plugins', array() );
		
		$this->response['slug'] = $slug;
		$this->plugin_head();
		$this->plugin_package();
	}
	
	function plugin_head() {
		
		$this->response['new_version']		= $this->plugins[$this->slug]['new_version'];		// 0.1.1.2
		$this->response['url']				= $this->plugins[$this->slug]['homepage'];			// http://pixeljar.net

	}
	
	function plugin_package() {
		/* URL to secure download link
		 * Blair mentioned that he dynamically generates this
		 * link to a script that generates the zip file
		 * dynamically based on a user's authentication
		/**/
		$this->response['package'] = site_url( '/wp-content/shopp-uploads/adsanity.zip' );
	}
}
/*
 * Show output
/**/
// add_action( 'wp', create_function( '', 'new pue_update_check( "adsanity/adsanity.php" );' ) );