<!-- GENERAL FIELDS -->
<div id="general">
	<table class="form-table">
	
	<!-- Version -->
	<tr>
		<th scope="row">Version</th>
		<td>
			<?php $version = ( isset( $pue_plugins[$_REQUEST['id']]['new_version'] ) ? $pue_plugins[$_REQUEST['id']]['new_version'] : '' ) ?>
			<input name="version" id="version" type="text" class="regular-text" value="<?php echo esc_attr( $version ) ?>" />
			<span class="description">Changing this will kick a notification out to all installations.</span>
		</td>
	</tr>
	
	<!-- Home Page URL -->
	<tr>
		<th scope="row">Home Page</th>
		<td>
			<?php $homepage = ( isset( $pue_plugins[$_REQUEST['id']]['homepage'] ) ? $pue_plugins[$_REQUEST['id']]['homepage'] : '' ) ?>
			<input name="homepage" id="homepage" type="text" class="regular-text" value="<?php echo esc_attr( $homepage ) ?>" />
			<span class="description">URL for the plugin (probably this web site).</span>
		</td>
	</tr>
	
	<!-- Plugin Name -->
	<tr>
		<th scope="row">Plugin Name</th>
		<td>
			<?php $plugin_name = ( isset( $pue_plugins[$_REQUEST['id']]['name'] ) ? $pue_plugins[$_REQUEST['id']]['name'] : '' ) ?>
			<input name="plugin-name" id="plugin-name" type="text" class="regular-text" value="<?php echo esc_attr( $plugin_name ) ?>" />
			<span class="description">Common name for this plugin.</span>
		</td>
	</tr>
	
	<!-- Plugin Author -->
	<tr>
		<th scope="row">Plugin Author</th>
		<td>
			<?php $plugin_author = ( isset( $pue_plugins[$_REQUEST['id']]['author'] ) ? $pue_plugins[$_REQUEST['id']]['author'] : '' ) ?>
			<input name="plugin-author" id="plugin-author" type="text" class="regular-text" value="<?php echo esc_attr( $plugin_author ) ?>" />
			<span class="description">Name of the individual or company who developed this plugin.</span>
		</td>
	</tr>
	
	<!-- Author Profile -->
	<tr>
		<th scope="row">Author Profile</th>
		<td>
			<?php $author_profile = ( isset( $pue_plugins[$_REQUEST['id']]['author_profile'] ) ? $pue_plugins[$_REQUEST['id']]['author_profile'] : '' ) ?>
			<input name="author-link" id="author-link" type="text" class="regular-text" value="<?php echo esc_attr( $author_profile ) ?>" />
			<span class="description">URL for the plugin author's wordpress.org or company home page.</span>
		</td>
	</tr>
	
	<!-- Contributors: Multiple Fields -->
	<tr>
		<th scope="row">Contributors</th>
		<td>
			<?php
				$contributor_count = false;
				$contributors = ( isset( $pue_plugins[$_REQUEST['id']]['contributors'] ) ? $pue_plugins[$_REQUEST['id']]['contributors'] : array() );
				foreach( $contributors as $name => $url ) :
			?>
			<input name="author-link" id="author-link" type="text" class="regular-text" value="<?php echo esc_attr( $name ) ?>" />
			<input name="author-link" id="author-link" type="text" class="regular-text" value="<?php echo esc_attr( $url ) ?>" />
			
			<?php if( !$contributor_count ) : ?>
			<span class="description">Name & URL (in that order)</span>
			<?php endif; ?>
			<br class="clear" />
			<?php
				if( !$contributor_count ) $contributor_count = true;
				endforeach;
			?>
			<a id="add-row" class="button" href="#">Add a contributor</a>
		</td>
	</tr>
	
	<!-- Min WP Version -->
	<tr>
		<th scope="row">Minimum WordPress Version</th>
		<td>
			<?php $min_wp = ( isset( $pue_plugins[$_REQUEST['id']]['requires'] ) ? $pue_plugins[$_REQUEST['id']]['requires'] : '' ) ?>
			<input name="author-link" id="author-link" type="text" class="regular-text" value="<?php echo esc_attr( $min_wp ) ?>" />
			<span class="description">URL for the plugin author's wordpress.org or company home page.</span>
		</td>
	</tr>
	
	<!-- Max WP Version -->
	<tr>
		<th scope="row">Maximum WordPress Version Tested</th>
		<td>
			<?php $max_wp = ( isset( $pue_plugins[$_REQUEST['id']]['tested'] ) ? $pue_plugins[$_REQUEST['id']]['tested'] : '' ) ?>
			<input name="author-link" id="author-link" type="text" class="regular-text" value="<?php echo esc_attr( $max_wp ) ?>" />
			<span class="description">URL for the plugin author's wordpress.org or company home page.</span>
		</td>
	</tr>
	
	<!-- Tags: Multiple Values -->
	<tr>
		<th scope="row">Tags</th>
		<td>
			<?php
				$tag_count = false;
				$tags = ( isset( $pue_plugins[$_REQUEST['id']]['tags'] ) ? $pue_plugins[$_REQUEST['id']]['tags'] : array() );
				foreach( $tags as $key => $val ) :
			?>
			<input name="tag-key[]" type="text" class="regular-text" value="<?php echo esc_attr( $key ) ?>" />
			<input name="tag-val[]" type="text" class="regular-text" value="<?php echo esc_attr( $val ) ?>" />
			
			<?php if( !$tag_count ) : ?>
			<span class="description">Tag slug & Tag name (in that order)</span>
			<?php endif ?>
			<br class="clear" />
			<?php
				if( !$tag_count ) $tag_count = true;
				endforeach;
			?>
			<a id="add-row" class="button" href="#">Add a tag</a>
		</td>
	</tr>
	
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
</div>