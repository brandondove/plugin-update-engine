<?php

class pue_plugin_cpt extends pj_cpt_base {
	
	var $version = '0.1';
	var $type = 'pue-plugin';
	var $singular = 'Plugin';
	var $plural = 'Plugins';
	var $metafields;
	
	function __construct() {
		add_filter( $this->type.'_setup', array( &$this, 'setup' ) );
		add_action( $this->type.'_init', array( &$this, 'taxonomies' ) );
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ) );
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ), 1 );
		add_filter( 'manage_pue-plugin_posts_columns', array( &$this, 'columns' ) );
		add_filter( 'manage_pue-plugin_posts_custom_column', array( &$this, 'column_values' ), 10, 2 );
		add_filter( 'manage_edit-pue-plugin_sortable_columns', array( &$this, 'sortable_columns' ) );
		
		// Post UI
		add_action( 'admin_print_scripts-post-new.php', array( &$this, 'admin_print_scripts' ) );
		add_action( 'admin_print_scripts-post.php', array( &$this, 'admin_print_scripts' ) );
		add_action( 'admin_print_scripts-edit.php', array( &$this, 'admin_print_scripts' ) );
		
		$this->metafields = array(// embed, str, float, int, date, file, path
			'target'		=> array( '_target',			'bool' ),
			'url'			=> array( '_url',				'url' ),
			'size'			=> array( '_size',				'str' ),
			'code'			=> array( '_code',				'raw' ),
			'start'			=> array( '_start_date',		'date' ),
			'end'			=> array( '_end_date',			'date' ),
		);
		parent::__construct();
	}
	
	function setup( $setup ) {
		$setup['rewrite'] = array( 'with_front' => false, 'slug' => 'ads' );
		$setup['supports'] = array( 'title', 'thumbnail', 'revisions' );
		$setup['exclude_from_search'] = true;
		return $setup;
	}
	function taxonomies() {
		$labels = array(
			'name' => _x( 'Groups', 'taxonomy general name' ),
			'singular_name' => _x( 'Group', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Ad Groups' ),
			'all_items' => __( 'All Groups' ),
			'parent_item' => __( 'Parent Group' ),
			'parent_item_colon' => __( 'Parent Group:' ),
			'edit_item' => __( 'Edit Group' ), 
			'update_item' => __( 'Update Group' ),
			'add_new_item' => __( 'Add New Group' ),
			'new_item_name' => __( 'New Group Name' ),
			'menu_name' => __( 'Ad Groups' ),
		); 	

		register_taxonomy(
			'ad-group',
			array( 'ads' ),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'with_front' => false, 'slug' => 'genre' ),
			)
		);
	}
	function columns( $columns ) {
		$columns = array(
			'cb'		=> '<input type="checkbox" />',
			'title'		=> __( 'Ad Title' ),
			'size'		=> __( 'Dimensions' ),
			'stats'		=> __( "Today's Stats" ),
			'start'		=> __( 'Display From' ),
			'expires'	=> __( 'Until' ),
		);
		return $columns;
	}
	function sortable_columns() {
		return array(
			'title'    => 'title',
			'size'		=> 'size',
			'start'		=> 'start_date',
			'expires'	=> 'end_date',
		);
	}
	function column_values( $column, $post_id ) {
		if( 'size' == $column ) :
			$size = get_post_meta( $post_id, '_size', true );
			$value = ( !$size ? '- not set -' : $size );
		elseif( 'stats' == $column ) :
			global $wp_locale;
			$now = mktime( 0,0,0, date("n"), date("j"), date("Y") );
			$view_key = '_views-'.$now;
			$views = get_post_meta( $post_id, $view_key, true );
			$views = ( !$views ? 0 : $views );
			
			$click_key = '_clicks-'.$now;
			$clicks = get_post_meta( $post_id, $click_key, true );
			$clicks = ( !$clicks ? 0 : $clicks );
			
			$value = $clicks. ' clicks / '.$views.' views / ';
			$value.= number_format( ( (int)$clicks > 0 && (int)$views > 0 ) ? ( (int)$clicks / (int)$views * 100 ) : '0', 2, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).'%';
		elseif( 'start' == $column ) :
			$start_date = get_post_meta( $post_id, '_start_date', true );
			$value = date( 'd F Y', ( !$start_date ? time() : $start_date ) );
		elseif( 'expires' == $column ) :
			$end_date = get_post_meta( $post_id, '_end_date', true );
			$value = date( 'd F Y', ( !$end_date ? time() : $end_date ) );
		endif;
		echo $value;
	}
	
	function edit_posts_per_page( $perpage, $posttype ) {
		if( $posttype == $this->type ) return -1;
		return $perpage;
	}
	
	function admin_print_scripts() {
		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
			wp_enqueue_script( 'ads-admin' );
			
			// BEGIN hack to highlight expired/expiring ads
			$now = time();
			$expired_ads = get_posts(
				array(
					'post_type' => 'ads',
					'meta_query' => array(
						array(
							'key' => '_end_date',
							'value' => $now,
							'type' => 'numeric',
							'compare' => '<'
						)
					),
					'nopaging' => true
				)
			);
			$expired_selectors = array();
			foreach( $expired_ads as $expired_ad ) :
				$expired_selectors[] = '#post-'.$expired_ad->ID;
			endforeach;
			
			$expiring_ads = get_posts(
				array(
					'post_type' => 'ads',
					'meta_query' => array(
						array(
							'key' => '_end_date',
							'value' => array( $now, $now + ( 60 * 60 * 24 * 7 ) ),
							'type' => 'numeric',
							'compare' => 'BETWEEN'
						)
					),
					'nopaging' => true
				)
			);
			$expiring_selectors = array();
			foreach( $expiring_ads as $expiring_ad ) :
				$expiring_selectors[] = '#post-'.$expiring_ad->ID;
			endforeach;
			
			wp_localize_script(
				'ads-admin',
				'adsanity',
				array(
					'expired_ads' => implode( ', ', $expired_selectors ),
					'expiring_ads' => implode( ', ', $expiring_selectors )
				)
			);
			// END hack to highlight expired/expiring ads
		endif;
	}
	
	function enter_title_here( $placeholder ) {
		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
			return __( 'Give this ad a title', LABOXING );
		endif;
		return $placeholder;
	}
	
	function add_meta_boxes() {
		add_meta_box(
			'ad-code',
			'Ad Code',
			array( &$this, 'ad_code_metabox' ),
			$this->type,
			'normal',
			'high'
		);
		add_meta_box(
			'ad-details',
			'Ad Details',
			array( &$this, 'ad_details_metabox' ),
			$this->type,
			'side'
		);
		add_meta_box(
			'ad-stats',
			'Ad Stats',
			array( &$this, 'ad_stats_metabox' ),
			$this->type,
			'normal',
			'high'
		);
	}
		function ad_code_metabox( $post ) {

			wp_nonce_field( $this->type.'-save_postmeta', $this->type.'_nonce' );
			
			echo '<p>Paste your ad code here. Just about anything is allowed here. Use with caution.</p>';
			echo '<textarea name="code" id="ad-code">'.get_post_meta( $post->ID, '_code', true ).'</textarea>';
		}
		function ad_details_metabox( $post ) {
			// OPEN IN A NEW WINDOW?
			echo '<label for="target">';
			echo '<input name="target" type="checkbox" value="1" id="target" '.checked( '1', get_post_meta( $post->ID, '_target', true ), false ).'> Open in a new window?';
			echo '</label>';
			
			// URL
			echo '<label for="url">Tracking URL</label>';
			echo '<input type="text" name="url" value="'.esc_attr( get_post_meta( $post->ID, '_url', true ) ).'" id="url">';
			
			// START DATE
			$start_date = get_post_meta( $post->ID, '_start_date', true );
			echo '<label for="start_day">Display From</label>';
			echo '<input type="text" name="start_day" value="'.esc_attr( date( 'd', ( !$start_date ? time() : $start_date ) ) ).'" id="start_day">';
			$this->select_months( 'start_month', date( 'F', ( !$start_date ? time() : $start_date ) ) );
			echo '<input type="text" name="start_year" value="'.esc_attr( date( 'Y', ( !$start_date ? time() : $start_date ) ) ).'" id="start_year">';
			// END DATE
			$end_date = get_post_meta( $post->ID, '_end_date', true );
			echo '<label for="end_day">Until</label>';
			echo '<input type="text" name="end_day" value="'.esc_attr( date( 'd', ( !$end_date ? time() : $end_date ) ) ).'" id="end_day">';
			$this->select_months( 'end_month', date( 'F', ( !$end_date ? time() : $end_date ) ) );
			echo '<input type="text" name="end_year" value="'.esc_attr( date( 'Y', ( !$end_date ? time() : $end_date ) ) ).'" id="end_year">';
			
			// SIZE
			echo '<label for="size">Ad Size</label>';
			echo '<select name="size" id="size" size="1">';
				$size = get_post_meta( $post->ID, '_size', true );
				// 140 wide
				echo '<option value="140x140"'.selected( $size, '140x140' ).'>140x140</option>';
				// 300 wide
				echo '<option value="300x250"'.selected( $size, '300x250' ).'>300x250</option>';
				echo '<option value="300x120"'.selected( $size, '300x120' ).'>300x120</option>';
				// 550 wide
				echo '<option value="550x200"'.selected( $size, '550x200' ).'>300x200</option>';
				echo '<option value="550x100"'.selected( $size, '550x100' ).'>550x100</option>';
				// 120 wide
				echo '<option value="120x600"'.selected( $size, '120x600' ).'>120x600</option>';
				echo '<option value="160x600"'.selected( $size, '160x600' ).'>160x600</option>';
				// OTHER
				echo '<option value="other"'.selected( $size, 'other' ).'>Other</option>';
			echo '</select>';
		}
		function ad_stats_metabox( $post ) {
			global $wp_locale;
			$today = mktime( 0,0,0, date("n"), (int)date("j")+1, date("Y") );
			$start = strtotime( '-8 days', $today );
			
			echo '<script type="text/javascript">'."\n";
			// views data array
			$views = array();
			for( $i = $start; $i <= $today; $i += ( 60*60*24 ) ) :
				$viewcount = get_post_meta( $post->ID, '_views-'.$i, true );
				$views[] = '['.( $i * 1000 ).','.( !$viewcount ? 0 : $viewcount ).']';
			endfor;
			echo 'var views = { data: ['.implode( ',', $views ).'], label: "Views", color: "#e9275d" };'."\n";
			
			// clicks data array
			$clicks = array();
			for( $i = $start; $i <= $today; $i += ( 60*60*24 ) ) :
				$clickcount = get_post_meta( $post->ID, '_clicks-'.$i, true );
				$clicks[] = '['.( $i * 1000 ).','.( !$clickcount ? 0 : $clickcount ).']';
			endfor;
			echo 'var clicks = { data: ['.implode( ',', $clicks ).'], label: "Clicks", color: "#4fb5d2" };'."\n";
			echo '</script>'."\n";
			echo '<p>The chart below shows the views and clicks for the last 7 days.</p>';
			$custom = get_post_custom( $post->ID );
			$clickcount = $viewcount = 0;
			foreach( $custom as $key => $arr ) :
				if( strpos( $key, '_views' ) !== false ) :
					$viewcount += (int)$arr[0];
				elseif( strpos( $key, '_clicks' ) !== false ) :
					$clickcount += (int)$arr[0];
				endif;
			endforeach;

			echo '<p><strong>All-Time Views:</strong> '.number_format( $viewcount, 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).'</p>';
			echo '<div id="views-chart" class="chart"></div>';
			
			echo '<p><strong>All-Time Clicks:</strong> '.number_format( $clickcount, 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).'</p>';
			echo '<div id="clicks-chart" class="chart"></div>';
		}
	function template_include( $template ) {
		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
			if( is_archive() ) :
				return ADSANITY_THEME.'loop-ads.php';
			else :
				if ( file_exists( STYLESHEETPATH.'/single-ads.php' ) ) :
					return STYLESHEETPATH.'/single-ads.php';
				elseif ( file_exists( TEMPLATEPATH.'/single-ads.php' ) ) :
					return TEMPLATEPATH.'/single-ads.php';
				else :
					return ADSANITY_THEME.'single-ads.php';
				endif;
			endif;
		endif;
		return $template;
	}
	function select_months( $input='', $selected='' ) {
		echo '<select name="'.$input.'" id="'.$input.'">';
		echo '<option'.selected( $selected, 'January' ).'>January</option>';
		echo '<option'.selected( $selected, 'February' ).'>February</option>';
		echo '<option'.selected( $selected, 'March' ).'>March</option>';
		echo '<option'.selected( $selected, 'April' ).'>April</option>';
		echo '<option'.selected( $selected, 'May' ).'>May</option>';
		echo '<option'.selected( $selected, 'June' ).'>June</option>';
		echo '<option'.selected( $selected, 'July' ).'>July</option>';
		echo '<option'.selected( $selected, 'August' ).'>August</option>';
		echo '<option'.selected( $selected, 'September' ).'>September</option>';
		echo '<option'.selected( $selected, 'October' ).'>October</option>';
		echo '<option'.selected( $selected, 'November' ).'>November</option>';
		echo '<option'.selected( $selected, 'December' ).'>December</option>';
		echo '</select>';
	}
	
	/*
	 * Creates the version part of the hash
	/**/
	function create_version_key( $post_id ) {
		update_post_meta( $this->ID, '_version_key', md5( $_POST['version'].time() ) );
	}
}
new ads_cpt;