/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Insert Table plugin.
 */

var dsRTE_insertTable = function() {

    /**
     * Execute Plugin.
     */
    this.ExecuteCommand = function( dsrte, arguments, panel ) {
    
        panel.slideToggle();
        return true;
    };

    /**
     * Prepare Plugin.
     * Attach a Click handler on the Ok button
     */
    this.PrepareCommand = function( dsrte, arguments, panel, $this ) {
    
        var args = arguments;
        var id = dsrte.iframe.id;
        $( '#'+id+'-'+args+'-ok' ).click( function() {
        
            var cols = parseInt( $( '#'+id+'-'+args+'-cols' ).val() );
            var rows = parseInt( $( '#'+id+'-'+args+'-rows' ).val() );
            if ( cols && rows ) {
            
                var tbl = '';
                for ( var i = 0; i < rows; i++ ) {
                    tbl += '<tr>';
                    for ( var j = 0; j < cols; j++ )
                        tbl += '<td>(' + i + ',' + j + ')</td>';
                    tbl += '</tr>';
                }
                dsrte.frame.focus();
                dsrte.PasteHTML( '<table>' + tbl + '</table>' );
                $( '#'+id+'-'+args+'-cols' ).val( '' );
                $( '#'+id+'-'+args+'-rows' ).val( '' );
            }

            panel.slideUp();
        } );

        // signal callback was successfully processed.
        return true;
    };
};

// Register new plugin with dsRTE
dsRTE.RegisterPlugin( new dsRTE_insertTable(), 'table' );

