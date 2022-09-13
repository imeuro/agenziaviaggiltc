<script type="text/template" id="customize-happyforms-radio-template">
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
	<p>
		<label for="<%= instance.id %>_description"><?php _e( 'Hint', 'happyforms' ); ?></label>
		<textarea id="<%= instance.id %>_description" data-bind="description"><%= instance.description %></textarea>
	</p>

	<?php do_action( 'happyforms_part_customize_radio_before_options' ); ?>

	<div class="options">
		<label><?php _e( 'List', 'happyforms' ); ?>:</label>
		<ul class="option-list"></ul>
		<p class="no-options description customize-control-description"><?php _e( 'It doesn\'t look like your field has any choices yet. Want to add one?
Click the "Add Choice" button to start.', 'happyforms' ); ?></p>
	</div>
	<p class="options-import">
		<label for="<%= instance.id %>_bulk_choices"><?php _e( 'Choices', 'happyforms' ); ?></label>
		<textarea id="<%= instance.id %>_bulk_choices" class="option-import-area" cols="30" rows="10"></textarea>
		<span class="customize-control-description"><?php _e( 'Type or paste your choices, adding each on a new line.' ); ?></span>
	</p>
	<p class="links mode-manual">
		<a href="#" class="button bulk-options centered"><?php _e( 'Bulk add choices', 'happyforms' ); ?></a>
		<a href="#" class="button add-heading"><?php _e( 'Add heading', 'happyforms' ); ?></a>
		<a href="#" class="button add-option centered"><?php _e( 'Add choice', 'happyforms' ); ?></a>
	</p>
	<p class="links mode-import">
		<a href="#" class="button add-import-options" disabled><?php _e( 'Add choices', 'happyforms' ); ?></a>
		<a href="#" class="button cancel-import-options"><?php _e( 'Cancel', 'happyforms' ); ?></a>
	</p>

	<?php do_action( 'happyforms_part_customize_radio_after_options' ); ?>

	<?php do_action( 'happyforms_part_customize_radio_before_advanced_options' ); ?>

	<% if ( instance.other_option ) { %>
		<p>
			<label>
				<input type="checkbox" class="checkbox" value="1" data-bind="other_option" checked /> <?php _e( 'Add \'other\' choice', 'happyforms' ); ?>
			</label>
		</p>
			<div class="happyforms-nested-settings" data-trigger="other_option" style="display: <%= ( instance.other_option ) ? 'block' : 'none' %>">
				<p>
					<label for="<%= instance.id %>_other_option_label"><?php _e( '\'Other\' label', 'happyforms' ); ?></label>
					<input type="text" id="<%= instance.id %>_other_option_label" maxlength="30" class="widefat title" value="<%= instance.other_option_label %>" data-bind="other_option_label" />
				</p>
				<p>
					<label for="<%= instance.id %>_other_option_placeholder"><?php _e( '\'Other\' placeholder', 'happyforms' ); ?></label>
					<input type="text" id="<%= instance.id %>_other_option_placeholder" maxlength="50" class="widefat title" value="<%= instance.other_option_placeholder %>" data-bind="other_option_placeholder" />
				</p>
			</div>
	<% } %>
	<p>
		<label>
			<input type="checkbox" class="checkbox" value="1" <% if ( instance.shuffle_options ) { %>checked="checked"<% } %> data-bind="shuffle_options" /> <?php _e( 'Shuffle order of choices', 'happyforms' ); ?>
		</label>
	</p>
	<p>
		<label for="<%= instance.id %>_display_type"><?php _e( 'Align choices', 'happyforms' ); ?></label>
		<span class="happyforms-buttongroup">
			<label for="<%= instance.id %>-display_type-vertical">
				<input type="radio" id="<%= instance.id %>-display_type-vertical" value="block" name="<%= instance.id %>-display_type" data-bind="display_type" <%= ( instance.display_type == 'block' ) ? 'checked' : '' %> />
				<span><?php _e( 'Vertically', 'happyforms' ); ?></span>
			</label>
			<label for="<%= instance.id %>-display_type-horizontal">
				<input type="radio" id="<%= instance.id %>-display_type-horizontal" value="inline" name="<%= instance.id %>-display_type" data-bind="display_type" <%= ( instance.display_type == 'inline' ) ? 'checked' : '' %> />
				<span><?php _e( 'Horizontally', 'happyforms' ); ?></span>
			</label>
		</span>
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

	<?php do_action( 'happyforms_part_customize_radio_after_advanced_options' ); ?>

	<div class="happyforms-part-logic-wrap">
		<div class="happyforms-logic-view">
			<?php happyforms_customize_part_logic(); ?>
		</div>
	</div>

	<?php happyforms_customize_part_footer(); ?>
</script>
<script type="text/template" id="customize-happyforms-radio-item-template">
	<li data-option-id="<%= id %>" class="happyforms-choice-item-widget">
		<div class="happyforms-part-item-handle">
			<div class="happyforms-part-item-advanced-option">
				<button type="button" class="happyforms-advanced-option-action">
					<span class="toggle-indicator"></span>
				</button>
			</div>
			<div class="happyforms-item-choice-widget-title">
				<h3><?php _e( 'Choice', 'happyforms' ); ?><span class="choice-in-widget-title">: <span><%= label %></span></span></h3>
			</div>
		</div>
		<div class="happyforms-part-item-body">
			<div class="happyforms-part-item-advanced">
				<p>
					<label>
						<?php _e( 'Label', 'happyforms' ); ?>:
						<input type="text" class="widefat" name="label" value="<%= label %>" data-option-attribute="label">
					</label>
				</p>
				<p>
					<label>
						<?php _e( 'Hint', 'happyforms' ); ?>:
						<textarea name="description" data-option-attribute="description"><%= description %></textarea>
					</label>
				</p>
				<p>
					<label>
						<?php _e( 'Max times this choice can be submitted', 'happyforms' ); ?>:
						<input type="number" class="widefat" name="limit_submissions_amount" min="0" value="<%= typeof limit_submissions_amount !== 'undefined' ? limit_submissions_amount : '' %>">
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="is_default" value="1" class="default-option-switch"<% if (is_default == 1) { %> checked="checked"<% } %>> <?php _e( 'Make this choice default', 'happyforms' ); ?>
					</label>
				</p>

				<div class="happyforms-part-choice-logic-wrap">
					<div class="happyforms-logic-view">
						<?php happyforms_customize_part_choice_logic(); ?>
					</div>
				</div>

				<?php happyforms_customize_part_choice_footer(); ?>
			</div>
		</div>
	</li>
</script>
<script type="text/template" id="customize-happyforms-radio-item-heading-template">
	<li data-option-id="<%= id %>" class="happyforms-choice-item-widget" data-is-heading="yes">
		<div class="happyforms-part-item-handle">
			<div class="happyforms-part-item-advanced-option">
				<button type="button" class="happyforms-advanced-option-action">
					<span class="toggle-indicator"></span>
				</button>
			</div>
			<div class="happyforms-item-choice-widget-title">
				<h3><?php _e( 'Heading', 'happyforms' ); ?><span class="choice-in-widget-title">: <span><%= label %></span></span></h3>
			</div>
		</div>
		<div class="happyforms-part-item-body">
			<div class="happyforms-part-item-advanced">
				<p>
					<label>
						<?php _e( 'Label', 'happyforms' ); ?>:
						<input type="text" class="widefat" name="label" value="<%= label %>" data-option-attribute="label">
					</label>
				</p>
				
				<div class="happyforms-part-choice-logic-wrap">
					<div class="happyforms-logic-view">
						<?php happyforms_customize_part_choice_logic(); ?>
					</div>
				</div>

				<?php happyforms_customize_part_choice_footer(); ?>
			</div>
		</div>
	</li>
</script>
