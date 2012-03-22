<?php

class pue_authenticated_user extends WP_User {
	
	function is_authorized( $plugin ) {
		return user_can( $this->ID, 'pue-download-'.$plugin );
	}
	
	function has_available_licenses( $plugin='', $url='http://google.com' ) {
		
		// Updated every time a request is made to authorize a domain
		$authorized_domains = get_user_meta( $this->ID, '_authorized_domains-'.$plugin, true );
		$authorized_domains = array_filter( $authorized_domains );
		
		// Set on purchase
		$allowed_domains = get_user_meta( $this->ID, '_allowed_domains-'.$plugin, true );
		/*
		 * To consider RE: licensing
		 *   How should we handle new domains if the user has maxed out
		 *   their licenses?
		 * 
		 *   Options: 
		 *   - Charge them for an additional domain activation automatically
		 *   - Add the new domain request to the authorized domains and pop off
		 *     one of the other ones
		/**/
		if( // Domain isn't authorized and user is out of licenses
			!in_array( $url, (array)$authorized_domains ) && // domain is not currently registered
			(
				count( (array)$authorized_domains ) > (int)$allowed_domains && // domains registered >= domains allowed (i.e. out of licenses)
				$allowed_domains != 'unlimited' // user doesn't have a developer license
			)
		) :
			return false;
		endif;
		
		/*
		 * Licenses are available because:
		 * 1. The domain in question has already been registered
		 * 2. The domain in question is new and the user still has licenses
		 * 3. The user has an unlimited license
		/**/
		update_user_meta(
			$this->ID,
			'_authorized_domains-'.$plugin,
			array_unique( array_merge( (array)$authorized_domains, array( esc_url( $url ) ) ) )
		);
		return true;
	}
	
	/*
	 * Creates the user part of the hash
	/**/
	function create_user_key() {
		update_user_meta( $this->ID, '_user_key', md5( $this->user_login.time() ) );
	}
}