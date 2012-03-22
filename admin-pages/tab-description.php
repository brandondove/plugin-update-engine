<!-- DESCRIPTION FIELDS -->
<div id="description">
	<table class="form-table">

	<!-- Description -->
	<tr>
		<th scope="row">Description</th>
		<td>
			<?php $description = ( isset( $pue_plugins[$_REQUEST['id']]['sections']['description'] ) ? $pue_plugins[$_REQUEST['id']]['sections']['description'] : '' ) ?>
			<?php the_editor( $description ); ?>
			<span class="description">Briefly describe what your plugin does.</span>
		</td>
	</tr>
	
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
</div>