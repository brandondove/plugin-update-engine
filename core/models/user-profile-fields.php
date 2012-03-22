<?php

/**
* 
*/
class pue_user_profile_fields {
	
	function __construct() {
		add_action( 'user_register', array( &$this, 'user_register' ) );
		add_action( 'edit_user_profile', array( &$this, 'show_user_profile' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'edit_user_profile_update' ) );
	}
	
	// HACK:this should probably be done on successful transaction
	function user_register( $user_id = 1 ) {
	
		if( user_can( $user_id, 'blogger' ) ) :
			update_user_meta( '_allowed_domains-adsanity', '1' );
			
		elseif( user_can( $user_id, 'publisher' ) ) :
			update_user_meta( '_allowed_domains-adsanity', '3' );
			
		elseif( user_can( $user_id, 'developer' ) ) :
			update_user_meta( '_allowed_domains-adsanity', 'unlimited' );
			
		endif;
	}
	
	// Show the number of licenses
	function show_user_profile( $user ) {
		?>
		<h3>AdSanity Licenses</h3>

		<table class="form-table">
		<tr>
			<th><label for="adsanity-licenses">AdSanity Licenses</label></th>
			<td>
				<input type="text" name="adsanity-licenses" id="adsanity-licenses" value="<?php echo esc_attr( get_user_meta( '_allowed_domains-adsanity', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">How many total licenses this user has.</span>
			</td>
		</tr>
		<?php
			$registered_domains = get_the_author_meta( '_authorized_domains-adsanity', $user->ID );
			$registered_domains = array_filter( $registered_domains );
			if( is_array( $registered_domains ) ) :
		?>
		<tr>
			<th>Registered Domains</th>
			<td>
				<ol>
				<?php
					foreach( (array)$registered_domains as $domain ) :
						echo '<li>'.esc_url( $domain ).'</li>';
					endforeach;
				?>
				</ol>
			</td>
		</tr>
		<?php endif; ?>
		</table>
		<?php
	}
	
	// update the number of licenses
	function edit_user_profile_update( $user_id ) {
		
		// Check if the logged in user is allowed to edit the displayed user
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;

		update_user_meta( $user_id, '_allowed_domains-adsanity', (int)$_POST['adsanity-licenses'] );
	}
}
new pue_user_profile_fields;