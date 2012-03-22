<?php

if( !function_exists( 'pring_r' ) ) :
	function pring_r( $arr ) {
		echo _pring_r( $arr );
	}
endif;

if( !function_exists( '_pring_r' ) ) :
	function _pring_r( $arr ) {
		echo '<pre>'.print_r( $arr, true ).'</pre>';
	}
endif;

if( !get_option( 'pue-plugins' ) ) :

	//* SAMPLE DATA
	$plugins = array();
		$adsanity_description = '<p>';
		$adsanity_description.= 'We initially built AdSanity to solve a problem that our client, the <a href="http://www.weddingchicks.com/" target="_blank">WeddingChicks</a> was having with another advertising plugin. They make part of their living through advertising revenue on their wedding inspiration blog and were frustrated with overly complicated banner management. With their high volume of traffic and large number of ads, they also found that the statistical information they were getting from other plugins were inaccurate. Due to the complex nature of the plugin, the SQL queries were also causing performance issues with the site. ';
		$adsanity_description.= '</p>';
		$adsanity_description.= '<p>';
		$adsanity_description.= 'AdSanity uses built-in WordPress functionality, so it is highly optimized, super accurate and easy to use.';
		$adsanity_description.= '</p>';
	
		$adsanity_changelog = '<h3>Version 1.0.1</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>Removed non-standard ad sizes from the defaults</li>';
		$adsanity_changelog.= '<li>Tracking filters can now be disabled by setting the constant ADSANITY_TRACK_THIS to true in wp-config.php</li>';
		$adsanity_changelog.= '<li>Fixed default CSS for 125s and 140s</li>';
		$adsanity_changelog.= '</ul>';
	
		$adsanity_changelog.= '<h3>Version 1.0</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>Launch!</li>';
		$adsanity_changelog.= '</ul>';
	
		$adsanity_changelog.= '<h3>Version 0.8</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>Created Ad Group widget</li>';
		$adsanity_changelog.= '<li>Pulled out admin-only stuff from the front-end</li>';
		$adsanity_changelog.= '<li>Abstracted ad display</li>';
		$adsanity_changelog.= '<li>Added a shortcode to display ads within posts</li>';
		$adsanity_changelog.= '<li>Added an AdRotate ad importer</li>';
		$adsanity_changelog.= '<li>Added an ajax search filter on the single ad widget to make it easier to find adswhen you have a lot of ads</li>';
		$adsanity_changelog.= '<li>Exclude ads from search results</li>';
		$adsanity_changelog.= "<li>Hide ads if they're expired</li>";
		$adsanity_changelog.= '<li>No paging on the ads page</li>';
		$adsanity_changelog.= '<li>Added css to highlight expired/expring ads in the ad list</li>';
		$adsanity_changelog.= "<li>Don't track clicks for people who can create ads</li>";
		$adsanity_changelog.= '</ul>';
	
		$adsanity_changelog.= '<h3>Version 0.7</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>Width/heights to setting textareas</li>';
		$adsanity_changelog.= '<li>Put checks in place to deny WordPress pingbacks/trackbacks to count as clicks</li>';
		$adsanity_changelog.= '<li>Fixed tracking error with some fancy SQL</li>';
		$adsanity_changelog.= '<li>Eliminate require_onces in favor of require</li>';
		$adsanity_changelog.= '<li>Added click support for doubleclick ads</li>';
		$adsanity_changelog.= '<li>Split view and click charts into separate graphs because the data is vastly different.</li>';
		$adsanity_changelog.= '<li>Fixed some bugs found in beta testing (thanks Kat!)</li>';
		$adsanity_changelog.= '</ul>';

		$adsanity_changelog.= '<h3>Version 0.2</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>First stable version of ADsanity</li>';
		$adsanity_changelog.= '<li>Check for debug functions before defining them</li>';
		$adsanity_changelog.= '<li>Added theme templates</li>';
		$adsanity_changelog.= '</ul>';
		
		$adsanity_changelog.= '<h3>Version 0.1</h3>';
		$adsanity_changelog.= '<ul>';
		$adsanity_changelog.= '<li>ADsanity was born</li>';
		$adsanity_changelog.= '</ul>';

		$adsanity = array(
			'new_version'		=> '1.0.1',
			'homepage'			=> site_url(),
			'name'				=> 'AdSanity',
			'author'			=> 'Pixel Jar',
			'author_profile'	=> 'http://pixeljar.net',
			'contributors'		=> array(
				'Brandon Dove'	=> 'http://brandondove.com',
				'Jeffrey Zinn'	=> 'http://jzinn.us',
			),
			'homepage'			=> 'http://adsanityplugin.com',
			'version'			=> '1.0.1',
			'requires'			=> '3.1.4',
			'tested'			=> '3.3',
			'last_updated'		=> date( 'Y-m-d' ),
			'sections'			=> array(
				'description'	=> $adsanity_description,
				'screenshots'	=> '<p>Screenshot 1</p>',
				'faq'			=> '<p>See our up-to-date FAQ at: <a href="http://adsanityplugin.com/faq/">http://adsanityplugin.com/faq/</a></p>',
				'changelog'		=> $adsanity_changelog,
			),
			'tags'				=> array( 'banner' => 'Banner', 'advertising' => 'Advertising', 'widget' => 'Widget' )
		);
	$plugins['adsanity/adsanity.php'] = $adsanity;
	update_option( 'pue-plugins', $plugins );
	/**/

endif;