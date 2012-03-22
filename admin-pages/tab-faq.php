<!-- FAQ FIELDS -->
<div id="faq">
	<table class="form-table">

	<!-- Description -->
	<tr>
		<th scope="row">Frequently Asked Questions</th>
		<td>
			<?php $faq = ( isset( $pue_plugins[$_REQUEST['id']]['sections']['faq'] ) ? $pue_plugins[$_REQUEST['id']]['sections']['faq'] : '' ) ?>
			<?php the_editor( $faq ); ?>
			<span class="description">Answer some commonly asked questions here.</span>
		</td>
	</tr>
	
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
</div>