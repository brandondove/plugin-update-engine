<!-- SCREENSHOT FIELDS -->
<div id="screenshots">
	<table class="form-table">
	
	<!-- Screenshot: Multiple Fields -->
	<tr>
		<th scope="row">Screenshot</th>
		<td>
			<?php
				$screenshot_count = false;
				$screenshots = ( isset( $pue_plugins[$_REQUEST['id']]['sections']['screenshots'] ) ? $pue_plugins[$_REQUEST['id']]['sections']['screenshots'] : array() );
				foreach( $screenshots as $url => $caption ) :
			?>
			<img src="<?php echo esc_attr( $url ) ?>" />
			<input name="screenshot[]" type="file" />
			<input name="caption[]" type="text" class="regular-text" value="<?php echo esc_attr( $key ) ?>" />
			
			<?php if( !$screenshot_count ) : ?>
			<span class="description">Add a caption to your photo</span>
			<?php endif ?>
			<br class="clear" />
			<?php
				if( !$screenshot_count ) $screenshot_count = true;
				endforeach;
			?>
			<a id="add-row" class="button" href="#">Add a screenshot</a>
		</td>
	</tr>
	
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
</div>