<script type="text/template" id="happyforms-form-build-template">
	<div class="happyforms-stack-view">
		<div class="customize-control">
			<input type="text" name="post_title" value="<%= post_title %>" id="happyforms-form-name" placeholder="<?php _e( 'Add title', 'happyforms' ); ?>">
		</div>

		<div class="customize-control">
			<div class="happyforms-parts-placeholder">
				<p><?php _e( 'It doesn\'t look like your form has any fields yet. Want to add one?
Click the "Add a Field" button to start.', 'happyforms' ); ?></p>
			</div>
			<div class="happyforms-form-widgets"></div>
			<button type="button" class="button add-new-widget happyforms-add-new-part"><?php _e( 'Add a Field', 'happyforms' ); ?></button>
		</div>
	</div>
</script>
