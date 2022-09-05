<script type="text/template" id="happyforms-form-steps-template">
	<nav class="nav-tab-wrapper">
		<a href="#" class="nav-tab<%= ( 'build' === happyForms.currentRoute ) ? ' nav-tab-active' : '' %>" data-step="build"><?php _e( 'Build', 'happyforms' ); ?></a>
		<a href="#" class="nav-tab<%= ( 'setup' === happyForms.currentRoute ) ? ' nav-tab-active' : '' %>" data-step="setup"><?php _e( 'Setup', 'happyforms' ); ?></a>
		<a href="#" class="nav-tab<%= ( 'email' === happyForms.currentRoute ) ? ' nav-tab-active' : '' %>" data-step="email"><?php _e( 'Emails', 'happyforms' ); ?></a>
		<a href="#" class="nav-tab<%= ( 'messages' === happyForms.currentRoute ) ? ' nav-tab-active' : '' %>" data-step="messages"><?php _e( 'Messages', 'happyforms' ); ?></a>
		<a href="#" class="nav-tab<%= ( 'style' === happyForms.currentRoute ) ? ' nav-tab-active' : '' %>" data-step="style"><?php _e( 'Styles', 'happyforms' ); ?></a>
	</nav>
</script>
