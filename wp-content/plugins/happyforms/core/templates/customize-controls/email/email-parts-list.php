<div class="customize-control" id="customize-control-<?php echo $control['field']; ?>">
    <?php do_action( "happyforms_setup_control_{$control['field']}_before", $control ); ?>

    <label for="<?php echo $control['field']; ?>" class="customize-control-title"><?php echo $control['label']; ?></label>
    <select id="<?php echo $control['field']; ?>" data-attribute="<?php echo $control['field']; ?>">
        <%
        var options = _( parts ).where( { type: 'email' } );

        options.forEach( function( option ) { %>
        <option value="<%= option.id %>"<%= ( option.id === <?php echo $control['field']; ?> ) ? ' selected' : '' %>>"<%= option.label %>" <?php _e( 'field', 'happyforms' ); ?></option>
        <% } ); %>

        <option value="all"<%= ( 'all' === <?php echo $control['field']; ?> ) ? ' selected' : '' %>><?php _e( 'All Email fields', 'happyforms' ); ?></option>
    </select>

    <?php do_action( "happyforms_setup_control_{$control['field']}_after", $control ); ?>
</div>