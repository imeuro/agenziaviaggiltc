<div class="wrap nosubsub">
	<h1 class="wp-heading-inline"><?php _e( 'Coupons', 'happyforms' ); ?></h1>
	<hr class="wp-header-end">
	<div id="ajax-response"></div>
	<div id="col-container" class="wp-clearfix">
		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap">
					<h2><?php _e( 'Add New Coupon', 'happyforms' ); ?></h2>
						<div class="form-field form-required">
							<label for="post_title"><?php _e( 'Name', 'happyforms' ); ?></label>
							<input name="post_title" id="post_title" type="text" value="" size="40" aria-required="true" />
							<p><?php _e( 'This is what will be applied by the submitter to receive a discount. The coupon must be unique and contain no spaces.', 'happyforms'); ?></p>
						</div>
						<div class="form-field form-required">
							<label for=""><?php _e( 'Discount Type', 'happyforms' ); ?></label>
							<span class="happyforms-buttongroup happyforms-buttongroup-field_width">
								<label for="discount_type_fixed">
									<input type="radio" id="discount_type_fixed" value="fixed" name="discount_type" checked="" disabled/>
									<span><?php _e( 'Fixed', 'happyforms' ); ?></span>
								</label>
								<label for="discount_type_percentage">
									<span><?php _e( 'Percentage', 'happyforms' ); ?></span>
								</label>
							</span>
						</div>
						<div class="form-field form-required">
							<label class="labels-dicount_type-fixed" for="discount_amount"><?php _e( 'Discount Amount', 'happyforms' ); ?></label>
							<input name="discount_amount" id="discount_amount" type="number" value="" size="40" aria-required="true" min="0" />
							<p class="details-discount_type-fixed"><?php _e( 'This amount automatically converts to whatever currency the form uses. For example, if this amount is &#8220;5&#8221; and the form uses dollars, the discount is $5. If the form uses euro, the discount is €5.', 'happyforms' ); ?></p>
						</div>
						<div class="form-field">
							<label for="description"><?php _e( 'Description', 'happyforms' ); ?></label>
							<textarea name="description" id="description" rows="5" cols="40"></textarea>
							<p><?php _e( 'This will not be seen by submitters.', 'happyforms' ); ?></p>
						</div>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Add New Coupon', 'happyforms' ); ?>" /><span class="spinner"></span>
						</p>
				</div>
			</div>
		</div>
		<div id="col-right">
			<div class="col-wrap">
				<table class="wp-list-table widefat fixed striped table-view-list coupons">
					<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
								<input id="cb-select-all-1" type="checkbox">
							</td>
							<th scope="col" id="post_title" class="manage-column column-post_title column-primary sortable desc">
								<a href="#"><span><?php _e( 'Name', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="description" class="manage-column column-description sortable desc">
								<a href=""><span><?php _e( 'Description', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th scope="col" id="redemptions" class="manage-column column-redemptions sortable desc">
								<a href=""><span><?php _e( 'Redemptions', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
							</th>
						</tr>
				</thead>
				<tbody id="the-list" data-wp-lists="list:coupon">
					<tr class="no-items">
						<td class="colspanchange" colspan="4"><?php _e( 'No coupons found.', 'happyforms' ); ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="post_title" class="manage-column column-post_title column-primary sortable desc">
							<a href="#"><span><?php _e( 'Name', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="description" class="manage-column column-description sortable desc">
							<a href=""><span><?php _e( 'Description', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" id="redemptions" class="manage-column column-redemptions sortable desc">
							<a href=""><span><?php _e( 'Redemptions', 'happyforms' ); ?></span><span class="sorting-indicator"></span></a>
						</th>
					</tr>
				</tfoot>

			</table>
			</div>
		</div>
	</div>
</div>
<style>
	.column-redemptions {
		width: 110px;
	}
</style>