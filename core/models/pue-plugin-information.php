<?php

/**
 * Plugin Information Response
 *
 * @package Plugin Update Engine
 * @author Pixel Jar
 * @version 0.1
 **/
class pue_plugin_information {
	
	var $plugins;				// set in __construct()
	var $slug;					// set in __construct()
	var $plugin_name;			// set in __construct()
	
	var $name;					// set in plugin_head()
	var $author;				// set in plugin_head()
	var $author_profile;		// set in plugin_head()
	var $contributors;			// set in plugin_head()
	var $homepage;				// set in plugin_head()
	var $version;				// set in plugin_head()
	var $new_version;			// set in plugin_head()
	var $requires;				// set in plugin_head()
	var $tested;				// set in plugin_head()
	var $last_updated;			// set in plugin_head()
	
	/*
	 * Each section set individually in internal functions
	 *
	 * description index set in plugin_description()
	 * screenshots index set in plugin_screenshots()
	 * faq index set in plugin_faq()
	 * changelog index set in plugin_changelog()
	/**/
	var $sections;
	
	var $tags;					// set in plugin_tags()
	var $download_link;			// set in plugin_download_link()
	var $response;				// attach everything to the response array
	
	function __construct( $slug ) {
		
		$this->slug = $slug;
		$this->response = array();
		
		// get all plugins managed by this plugin
		// talk about a swirling vortex of DOOM!
		$this->plugins = get_option( 'pue-plugins', array() );
		
		$this->response['slug'] = $this->response['plugin_name'] = $slug;
		$this->plugin_head();
		$this->plugin_description();
		$this->plugin_screenshots();
		$this->plugin_faq();
		$this->plugin_changelog();
		$this->plugin_tags();
		$this->plugin_download_link();
		return $this;
	}
	
	function plugin_head() {
		
		$this->response['name']				= $this->plugins[$this->slug]['name'];				// e.g. ADSanity
		$this->response['author']			= $this->plugins[$this->slug]['author'];			// Pixel Jar
		$this->response['author_profile']	= $this->plugins[$this->slug]['author_profile'];	// http://wordpress.org/extend/plugins/profile/brandondove
		$this->response['contributors']		= $this->plugins[$this->slug]['contributors'];		// array( 'Brandon Dove' => 'http://brandondove.com', 'Jeffrey Zinn' => 'http://jzinn.us' )
		$this->response['homepage']			= $this->plugins[$this->slug]['homepage'];			// http://pixeljar.net
		$this->response['version']			= $this->plugins[$this->slug]['version'];			// 0.1.1.1
		$this->response['new_version']		= $this->plugins[$this->slug]['new_version'];		// 0.1.1.2
		$this->response['requires']			= $this->plugins[$this->slug]['requires'];			// 3.2.1 (i.e. WordPress version)
		$this->response['tested']			= $this->plugins[$this->slug]['tested'];			// 3.2.1 (i.e. WordPress version)
		$this->response['last_updated']		= $this->plugins[$this->slug]['last_updated'];		// date( 'Y-m-d' )
		
		$this->response['sections'] = array();
	}
	
	function plugin_description() {
		// Just about any HTML allowed
		$this->response['sections']['description'] = $this->plugins[$this->slug]['sections']['description'];
	}
	
	function plugin_screenshots() {
		// One paragraph tag per screenshot (I think)
		$this->response['sections']['screenshots'] = $this->plugins[$this->slug]['sections']['screenshots'];
	}
	
	function plugin_faq() {
		// Just about any HTML allowed
		$this->response['sections']['faq'] = $this->plugins[$this->slug]['sections']['faq'];
	}
	
	function plugin_changelog() {
		// Just about any HTML allowed
		$this->response['sections']['changelog'] = $this->plugins[$this->slug]['sections']['changelog'];
	}
	
	function plugin_tags() {
		// array( 'tag-slug' => 'Tag Name' )
		$this->response['tags'] = $this->plugins[$this->slug]['tags'];
	}
	
	function plugin_download_link() {
		/* URL to secure download link
		 * Blair mentioned that he dynamically generates this
		 * link to a script that generates the zip file
		 * dynamically based on a user's authentication
		/**/
		$this->response['download_link'] = site_url( '/wp-content/shopp-uploads/adsanity.zip' );
	}
}
/*
 * Show output
/**/
// add_action( 'wp', create_function( '', 'new pue_plugin_information( "adsanity/adsanity.php" );' ) );