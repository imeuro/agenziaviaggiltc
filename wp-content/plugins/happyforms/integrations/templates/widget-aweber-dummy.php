<?php
$label_link = ' (<a href="#" target="_blank">' . __( 'get your code', 'happyforms' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="11 -4 1 24" width="20" height="18" class="components-external-link__icon css-bqq7t3 etxm6pv0" role="img" aria-hidden="true" focusable="false"><path d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"></path></svg></a>)';
?>
<form class="happyforms-service">
	<div class="widget-content">
		<div id="happyforms-service-aweber" class="happyforms-service-integration">
			<div class="widget-content">
				<div class="oauth-flow">
					<label for=""><?php echo __( 'Verification code', 'happyforms' ) . $label_link; ?></label>
					<div class="hf-pwd">
						<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
						<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
							<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="widget-control-actions">
			<div class="alignleft">
				<input type="submit" class="connected button button-primary widget-control-save right" value="<?php _e( 'Save Changes', 'happyforms' ); ?>">
			</div>
			<br class="clear" />
		</div>
	</div>
</form>
