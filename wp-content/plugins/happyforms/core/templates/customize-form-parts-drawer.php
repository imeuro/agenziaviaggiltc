<script type="text/template" id="happyforms-form-parts-drawer-template">
	<div id="happyforms-parts-drawer">
		<div class="happyforms-parts-drawer-header">
			<div class="happyforms-parts-drawer-header-search">
				<input type="text" placeholder="<?php _e( 'Search fields', 'happyforms' ); ?>&hellip;" id="part-search">
				<div class="happyforms-parts-drawer-header-search-icon"></div>
				<button type="button" class="happyforms-clear-search"><span class="screen-reader-text"><?php _e( 'Clear Results', 'happyforms' ); ?></span></button>
			</div>
		</div>
		<ul class="happyforms-parts-list">
			<% for (var p = 0; p < parts.length; p ++) { var part = parts[p]; %>
			<%
				var customClass = '';
				var isDummy = false;
				var isGroup = false;

				if ( -1 !== part.type.indexOf( 'dummy' ) ) {
					isDummy = true;
				}

				if ( 'drawer_group' === part.group ) {
					isGroup = true;
				}

				if ( isDummy ) {
					customClass = ' happyforms-parts-list-item--dummy';
				}

				if ( isGroup ) {
					customClass = ' happyforms-parts-list-item--group';
				}
			%>
			<li class="happyforms-parts-list-item<%= customClass %>" data-part-type="<%= part.type %>">
				<div class="happyforms-parts-list-item-content">
					<div class="happyforms-parts-list-item-title">
						<h3><%= part.label %></h3>
						<% if ( isDummy ) { %>&nbsp;<span class="members-only"><?php _e( 'Members Only', 'happyforms') ?></span><% } %>
					</div>
					<div class="happyforms-parts-list-item-description"><%= part.description %></div>
				</div>
			</li>
			<% } %>
		</ul>
		<div class="happyforms-parts-drawer-not-found">
			<p><?php _e( 'No fields found.', 'happyforms' ); ?></p>
		</div>
	</div>
</script>
