<div class="customize-control customize-control-email-parts-list_dummy" id="customize-control-<?php echo $control['dummy_id']; ?>">
    <label for="<?php echo $control['dummy_id']; ?>" class="customize-control-title"><?php echo $control['label']; ?></label>&nbsp<span class="members-only"><?php _e( 'Members Only', 'happyforms') ?></span>
    <select id="<?php echo $control['dummy_id']; ?>">
        <%
        var options = _( parts ).where( { type: 'email' } );

        options.forEach( function( option, i ) { %>
        <option value="<%= option.id %>" <%= ( 0 === i ) ? 'selected' : '' %>>"<%= option.label %>" <?php _e( 'field', 'happyforms' ); ?></option>
        <% } ); %>
    </select>
</div>