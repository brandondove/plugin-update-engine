<?php

// we'll set up a base class for the cpts to extend to keep things DRY
if( !class_exists( 'pj_cpt_base' ) ) :
	class pj_cpt_base {
	
		var $version = '0.3';
		var $type;
		var $singular;
		var $plural;
	
		function __construct() {
			add_action( 'init', array( &$this, 'init' ), 0 );
			add_action( 'save_post', array( &$this, 'save_post' ) );
		
			// Post UI
			add_action( 'admin_print_styles-post-new.php', array( &$this, 'admin_print_styles' ) );
			add_action( 'admin_print_styles-post.php', array( &$this, 'admin_print_styles' ) );
			add_action( 'admin_print_styles-edit.php', array( &$this, 'admin_print_styles' ) );
		
			// Taxonomy UI
			add_action( 'admin_print_styles-edit-tags.php', array( &$this, 'admin_print_styles' ) );
		
			// Post Type Order UI
			add_action( 'admin_print_styles-'.$this->type.'_page_order-post-types-'.$this->type, array( &$this, 'admin_print_styles' ) );
		}
	
		function init( $type = 'pj', $custom = array(), $labels = array() ) {
		
			$label_defaults = array(
				'name'					=> _x( $this->plural, 'post type general name' ),
				'singular_name'			=> _x( $this->singular, 'post type singular name' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( sprintf( 'Add New %s', $this->singular ) ),
				'edit_item'				=> __( sprintf( 'Edit %s', $this->singular ) ),
				'new_item'				=> __( sprintf( 'New %s', $this->singular ) ),
				'view_item'				=> __( sprintf( 'View %s', $this->singular ) ),
				'search_items'			=> __( sprintf( 'Search %s', $this->plural ) ),
				'not_found'				=> __( sprintf( 'No %s found', strtolower( $this->plural ) ) ),
				'not_found_in_trash'	=> __( sprintf( 'No %s found in Trash', strtolower( $this->plural ) ) ), 
				'parent_item_colon'		=> '',
				'menu_name'				=> $this->plural
			);
		
			$setup_defaults = array(
				'labels' => apply_filters( $this->type.'_labels', $label_defaults ),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'show_in_menu' => true, 
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => true, 
				'hierarchical' => false,
				'menu_position' => null,
				'menu_icon' => plugin_dir_url( __FILE__ ).'../images/cpt-menuicon-'.$this->type.'.png',
				'supports' => array('title','editor','author','thumbnail','excerpt','comments')
			);
		
			register_post_type( $this->type, apply_filters( $this->type.'_setup', $setup_defaults ) );
		
			// For hooking in custom taxonomies
			do_action( $this->type.'_init' );
		}
	
		function admin_print_styles() {
			if( get_query_var('post_type') == $this->type || get_post_type() == $this->type || $_REQUEST['post_type'] == $this->type ) :
				wp_enqueue_style( $this->type.'-admin', plugin_dir_url( __FILE__ ).'../css/'.$this->type.'.css', array(), $this->version, 'screen' );
			endif;
		}
	
		function save_post( $post_id ) {
			
			// autosaves kill post meta
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
			
			// user has permission
			if( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
			
			// verify intent
			$nonce_field = apply_filters( $this->type.'_nonce_field', $this->type.'_nonce' );
			if( !wp_verify_nonce( $_POST[$nonce_field], $this->type.'-save_postmeta' ) ) return $post_id;
			
			$dirty = $_POST;
			$dirty_files = $_FILES;
		
			// loop through the post and sanitize the data
			foreach( $this->metafields as $name => $meta ) :
		
				$clean = false;
		
				// field was submitted and not empty
				if( isset( $dirty[$name] ) || ( isset( $dirty_files[$name] ) && $meta[1] = 'file' ) || $meta[1] = 'date' ) :
		
					// SANITIZE DATA
					switch( $meta[1] ) :
						case 'embed':
							$dirty[$name] = stripslashes_deep( $dirty[$name] );
							$clean = wp_kses(
								$dirty[$name],
								array(
									'iframe' => array(
										'src' => array(),
										'frameborder' => array(),
										'allowfullscreen' => array()
									),
									'object' => array(),
									'param' => array(
										'name' => array(),
										'value' => array()
									),
									'embed' => array(
										'src' => array(),
										'type' => array(),
										'allowscriptaccess' => array(),
										'allowfullscreen' => array()
									)
								)
							);
							break;
						case 'str':
							$clean = wp_kses_data( $dirty[$name] );
							break;
						case 'url':
							$clean = esc_url_raw( $dirty[$name] );
							break;
						case 'phone':
							$matches = array();
							preg_match( '/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/', $dirty[$name], $matches );
							$clean = sprintf( "%s.%s.%s", $matches[1], $matches[2], $matches[3] );
							break;
						case 'email':
							if( preg_match( '/^(([a-zA-Z0-9_.\-+!#$&\'*+=?^`{|}~])+\@((([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+|localhost) *,? *)+$/', $dirty[$name] ) ) :
								$clean = $dirty[$name];
							endif;
							break;
						case 'float':
							$clean = floatval( $dirty[$name] );
							break;
						case 'int':
							$clean = intval( $dirty[$name] );
							break;
						case 'date':
							if( isset( $dirty[$name.'_day'] ) && isset( $dirty[$name.'_month'] ) && isset( $dirty[$name.'_year'] ) ) :
								$clean = strtotime( $dirty[$name.'_day'].' '.$dirty[$name.'_month'].' '.$dirty[$name.'_year'] );
							else :
								$clean = strtotime( $dirty[$name] );
							endif;
							break;
						case 'bool':
							$clean = isset( $dirty[$name] ) ? (bool)$dirty[$name] : 0;
							break;
						case 'path':
							$clean = ( is_file( $dirty[$name] ) ? stripslashes_deep( $dirty[$name] ) : serialize( array( 'error' => 'File does not exist.' ) ) );
							break;
						case 'file':
							if ( empty( $dirty_files[$name]['error'] )) :
					            /* Require WordPress utility functions for handling media uploads */
					            require_once( ABSPATH . '/wp-admin/includes/media.php' );
					            require_once( ABSPATH . '/wp-admin/includes/image.php' );
					            require_once( ABSPATH . '/wp-admin/includes/file.php' );
				                $clean = media_handle_upload( $name, $post_id );
								if( is_wp_error( $clean ) ) :
									$clean = false;
									return false;
								endif;
				            endif;
							break;
						case 'raw':
							// USE SPARINGLY.
							// THIS ALLOWS UNTRUSTED DATA STRAIGHT INTO THE DATABASE.
							// YIKES!
							$clean = $dirty[$name];
							break;
					endswitch;
			
					if( (bool)$clean === true ) :
						update_post_meta( $post_id, $meta[0], $clean );
					elseif ( empty( $clean ) && $meta[1] != 'file' ) :
						delete_post_meta( $post_id, $meta[0] );
					endif;
				
				
				elseif( !isset( $dirty[$name] ) && $meta[1] == 'bool' ) :
					delete_post_meta( $post_id, $meta[0] );
				endif;
		
			endforeach;
		}
	}
endif;