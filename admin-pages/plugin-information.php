<?php

class pue_plugin_information_editor {

	function __construct() {
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
	}
	
	function admin_menu() {
		add_plugins_page( 'Plugin Update Engine', 'Plugin Update Engine', 'update_core', 'plugin-update-engine', array( &$this, 'version_editor' ) );
	}
	
	function version_editor() {

		$defaults = array(
			'version'		=> '',
			'homepage'		=> '',
			'name'			=> '',
			'author'		=> '',
			'author_profile'=> '',
			'contributors'	=> array(),
			'version'		=> '',
			'requires'		=> '',
			'tested'		=> '',
			'last_updated'	=> date( 'Y-m-d' ),
			'sections'		=> array(
				'description'	=> '',
				'screenshots'	=> '',
				'faq'			=> '',
				'changelog'		=> '',
			),
			'tags'			=> array(),
		);
	
		$pue_plugins = get_option( 'pue-plugins', $defaults );
		
		$base_url = add_query_arg( array(
			'action'	=> 'edit',
			'id'		=> ( isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : false )
		));
		?>
		
		<div class="wrap">
		
			<?php screen_icon( 'plugins' ) ?>
			<h2>Plugin Update Engine <a href="<?php echo add_query_arg( array( 'action' => 'new', 'id' => false ) ) ?>" class="add-new-h2">Add New</a></h2>
			
			<!-- navigation -->
			<ul class="subsubsub">
			<?php
				$plugin_count = false;
				foreach( $pue_plugins as $slug => $info ) :
				
					echo '<option>';
					echo '<a href="'.add_query_arg( array( 'id' => $slug ), $base_url ).'"'.( !$plugin_count && !isset( $_REQUEST['id'] ) ? ' class="current"' : '' ).'>';
					echo ucwords( implode( ' ', explode( '-', basename( $slug, '.php' ) ) ) );
					echo '</a>';
					echo '</option>';
					
					if( !$plugin_count ) $plugin_count = true;
			
				endforeach;
			?>
			</ul>
			<br class="clear" />
			
			<form method="post">
			<?php wp_nonce_field( 'plugin_update_engine-update-plugin-information' ) ?>
			<h2 class="nav-tab-wrapper" style="padding-left: 6px;">
				<a class="nav-tab<?php echo ( !isset( $_REQUEST['tab'] ) || $_REQUEST['tab'] == 'general' ) ? ' nav-tab-active' : '' ?>" href="<?php echo add_query_arg( array( 'tab' => 'general' ), $base_url ); ?>">General Information</a>
				<a class="nav-tab<?php echo ( $_REQUEST['tab'] == 'description' ) ? ' nav-tab-active' : '' ?>" href="<?php echo add_query_arg( array( 'tab' => 'description' ), $base_url ); ?>">Description</a>
				<a class="nav-tab<?php echo ( $_REQUEST['tab'] == 'screenshots' ) ? ' nav-tab-active' : '' ?>" href="<?php echo add_query_arg( array( 'tab' => 'screenshots' ), $base_url ); ?>">Screenshots</a>
				<a class="nav-tab<?php echo ( $_REQUEST['tab'] == 'faq' ) ? ' nav-tab-active' : '' ?>" href="<?php echo add_query_arg( array( 'tab' => 'faq' ), $base_url ); ?>">FAQ</a>
				<a class="nav-tab<?php echo ( $_REQUEST['tab'] == 'changelog' ) ? ' nav-tab-active' : '' ?>" href="<?php echo add_query_arg( array( 'tab' => 'changelog' ), $base_url ); ?>">Changelog</a>
			</h2>
			<br class="clear" />
			
			<?php
				if( !isset( $_REQUEST['tab'] ) ||  $_REQUEST['tab'] == 'general' ) :
					require_once( PUENGINE_ADMIN_PAGES.'tab-general.php' );
					
				elseif( $_REQUEST['tab'] == 'description' ) :
					require_once( PUENGINE_ADMIN_PAGES.'tab-description.php' );
					
				elseif( $_REQUEST['tab'] == 'screenshots' ) :
					require_once( PUENGINE_ADMIN_PAGES.'tab-screenshots.php' );
					
				elseif( $_REQUEST['tab'] == 'faq' ) :
					require_once( PUENGINE_ADMIN_PAGES.'tab-faq.php' );
					
				elseif( $_REQUEST['tab'] == 'changelog' ) :
					require_once( PUENGINE_ADMIN_PAGES.'tab-changelog.php' );
				endif;
			?>
			</form>
		</div>
		<?php
	}
}
new pue_plugin_information_editor;