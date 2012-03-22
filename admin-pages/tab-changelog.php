<!-- CHANGELOG FIELDS -->
<div id="changelog">
	<table class="form-table">
	
	<!-- Changelog -->
	<tr>
		<th scope="row">Changelog</th>
		<td>
			<?php $changelog = ( isset( $pue_plugins[$_REQUEST['id']]['sections']['changelog'] ) ? $pue_plugins[$_REQUEST['id']]['sections']['changelog'] : '' ) ?>
			<?php the_editor( $changelog ); ?>
			<span class="description">Detail out the changes you've made to your plugin.</span>
		</td>
	</tr>
	
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
</div>