<script type="text/template" id="happyforms-customize-number-template">
	<?php include( happyforms_get_core_folder() . '/templates/customize-form-part-header.php' ); ?>
	<p class="label-field-group">
		<label for="<%= instance.id %>_title"><?php _e( 'Label', 'happyforms' ); ?></label>
		<div class="label-group">
			<input type="text" id="<%= instance.id %>_title" class="widefat title" value="<%= instance.label %>" data-bind="label" />
			<div class="happyforms-buttongroup">
				<label for="<%= instance.id %>-label_placement-show">
					<input type="radio" id="<%= instance.id %>-label_placement-show" value="show" name="<%= instance.id %>-label_placement" data-bind="label_placement" <%= ( instance.label_placement == 'show' ) ? 'checked' : '' %> />
					<span><?php _e( 'Show', 'happyforms' ); ?></span>
				</label>
				<label for="<%= instance.id %>-label_placement-hidden">
					<input type="radio" id="<%= instance.id %>-label_placement-hidden" value="hidden" name="<%= instance.id %>-label_placement" data-bind="label_placement" <%= ( instance.label_placement == 'hidden' ) ? 'checked' : '' %> />
					<span><?php _e( 'Hide', 'happyforms' ); ?></span>
				</label>
 			</div>
		</div>
	</p>
	<p class="happyforms-placeholder-option" style="display: <%= ( 'as_placeholder' !== instance.label_placement ) ? 'block' : 'none' %>">
		<label for="<%= instance.id %>_placeholder"><?php _e( 'Placeholder', 'happyforms' ); ?></label>
		<input type="text" id="<%= instance.id %>_placeholder" class="widefat title" value="<%= instance.placeholder %>" data-bind="placeholder" />
	</p>
	<p class="happyforms-default-value-option">
		<label for="<%= instance.id %>_default_value"><?php _e( 'Prefill', 'happyforms' ); ?></label>
		<input type="number" id="<%= instance.id %>_default_value" class="widefat title default_value" value="<%= instance.default_value %>" data-bind="default_value" />
	</p>
	<p>
		<label for="<%= instance.id %>_description"><?php _e( 'Hint', 'happyforms' ); ?></label>
		<textarea id="<%= instance.id %>_description" data-bind="description"><%= instance.description %></textarea>
	</p>

	<?php do_action( 'happyforms_part_customize_number_before_options' ); ?>

	<p>
		<label for="<%= instance.id %>_min_value"><?php _e( 'Min number', 'happyforms' ); ?></label>
		<input type="number" id="<%= instance.id %>_min_value" class="widefat title" value="<%= instance.min_value %>" data-bind="min_value" />
	</p>
	<p>
		<label for="<%= instance.id %>_max_value"><?php _e( 'Max number', 'happyforms' ); ?></label>
		<input type="number" id="<%= instance.id %>_max_value" class="widefat title" value="<%= instance.max_value %>" data-bind="max_value" />
	</p>
	<p>
		<label for="<%= instance.id %>_step"><?php _e( 'Step Interval', 'happyforms' ); ?></label>
		<input type="number" id="<%= instance.id %>_step" class="widefat title" value="<%= instance.step %>" data-bind="step" />
	</p>

	<?php do_action( 'happyforms_part_customize_number_after_options' ); ?>

	<?php do_action( 'happyforms_part_customize_number_before_advanced_options' ); ?>

	<p>
		<label>
			<input type="checkbox" name="masked" class="checkbox" value="1" <% if ( instance.masked ) { %>checked="checked"<% } %> data-bind="masked" /> <?php _e( 'Use number separators', 'happyforms' ); ?>
		</label>
	</p>
	<div class="happyforms-nested-settings mask-wrapper number-options number-options--numeric" data-trigger="masked" style="display: <%= (instance.masked == 1) ? 'flex' : 'none' %>">
		<p>
			<label for="<%= instance.id %>_mask_numeric_thousands_delimiter"><?php _e( 'Grouping', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_mask_numeric_thousands_delimiter" class="widefat title" value="<%= instance.mask_numeric_thousands_delimiter %>" data-bind="mask_numeric_thousands_delimiter" />
		</p>
		<p>
			<label for="<%= instance.id %>_mask_numeric_decimal_mark"><?php _e( 'Decimal', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_mask_numeric_decimal_mark" class="widefat title" value="<%= instance.mask_numeric_decimal_mark %>" data-bind="mask_numeric_decimal_mark" />
		</p>
	</div>
	<p>
		<label for="<%= instance.id %>_mask_numeric_prefix"><?php _e( 'Prefix', 'happyforms' ); ?></label>
		<input type="text" id="<%= instance.id %>_mask_numeric_prefix" class="widefat title" value="<%= instance.mask_numeric_prefix %>" data-bind="mask_numeric_prefix" maxlength="50" />
	</p>
	<p>
		<label for="<%= instance.id %>_mask_numeric_suffix"><?php _e( 'Suffix', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_mask_numeric_suffix" class="widefat title" value="<%= instance.mask_numeric_suffix %>" data-bind="mask_numeric_suffix" maxlength="50" />
	</p>
	<p>
		<label>
			<input type="checkbox" class="checkbox" value="1" <% if ( instance.required ) { %>checked="checked"<% } %> data-bind="required" /> <?php _e( 'Require an answer', 'happyforms' ); ?>
		</label>
	</p>

	<?php happyforms_customize_part_width_control(); ?>

	<p>
		<label for="<%= instance.id %>_css_class"><?php _e( 'Additional CSS class(es)', 'happyforms' ); ?></label>
		<input type="text" id="<%= instance.id %>_css_class" class="widefat title" value="<%= instance.css_class %>" data-bind="css_class" />
	</p>

	<?php do_action( 'happyforms_part_customize_number_after_advanced_options' ); ?>

	<div class="happyforms-part-logic-wrap">
		<div class="happyforms-logic-view">
			<?php happyforms_customize_part_logic(); ?>
		</div>
	</div>

	<?php happyforms_customize_part_footer(); ?>
</script>
