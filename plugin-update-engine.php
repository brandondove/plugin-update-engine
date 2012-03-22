<?php

/*
Plugin Name: Plugin Update Engine
Plugin URI: http://pixeljar.net
Description: This plugin is a server side tool that allows premium plugins to connect to it, authenticate access and download updates.
Version: 0.1
Author: Pixel Jar
Author URI: http://pixeljar.net 
*/

/**
 * Copyright (c) 2011 Pixel Jar. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// SET UP PATH CONSTANTS
define( 'PUENGINE',					'plugin-update-engine' );
define( 'PUENGINE_VERSION',			'0.1' );
define( 'PUENGINE_URL',				plugin_dir_url( __FILE__ ) );
define( 'PUENGINE_ABS',				plugin_dir_path( __FILE__ ) );
define( 'PUENGINE_REL',				basename( dirname( __FILE__ ) ) );
define( 'PUENGINE_ADMIN_PAGES',		PUENGINE_ABS.'admin-pages/' );
define( 'PUENGINE_CORE',			PUENGINE_ABS.'core/' );
define( 'PUENGINE_LIB',				PUENGINE_ABS.'lib/' );
define( 'PUENGINE_LANG',			PUENGINE_ABS.'i18n/' );
define( 'PUENGINE_CSS',				PUENGINE_URL.'css/' );
define( 'PUENGINE_JS',				PUENGINE_URL.'js/' );
define( 'PUENGINE_ADMIN_OPTIONS',	'plugin-update-engine-options' );
define( 'PUENGINE_SALT',			'vimUNpvNwxqhmtABygmgk2bstoyTK6WL' );

// INTERNATIONALIZATION
load_plugin_textdomain( PUENGINE, null, PUENGINE_REL );

/*
 * Nonsense responses for illegal requests
 * just to keep things fun
/**/
$pue_crazy_responses = array(
	__( "OH! You cheeky little monkey!", 'plugin-update-engine' ),
	__( "If Chuck Norris saw you trying to do that, He'd kick your ass.", 'plugin-update-engine' ),
	__( "Cheatin&#8217; uh?", 'plugin-update-engine' ),
);
require_once( PUENGINE_LIB.'debug.php' );

/*
 * Routes all remote plugin requests for:
 * - Authentication
 * - Downloads
 * - Update Checks
 * - Detailed Update Information
/**/
require_once( PUENGINE_CORE.'controllers/pue-posts.php' );

/*
 * Routes all remote plugin requests for:
 * - Plugin Authentication
/**/
require_once( PUENGINE_CORE.'controllers/pue-xmlrpc.php' );

/*
 * Adds fields to the user profile
/**/
require_once( PUENGINE_CORE.'models/user-profile-fields.php' );

require_once( PUENGINE_ADMIN_PAGES.'plugin-information.php' );