/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Insert Link plugin.
 */

var dsRTE_insertLink = function() {

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
        $( '#'+dsrte.iframe.id+'-'+arguments+'-ok' ).click( function() {
        
            var url = $( '#'+dsrte.iframe.id+'-'+args+'-url' ).val();
            var tgt = $( '#'+dsrte.iframe.id+'-'+args+'-target' ).val();
            if ( url ) {
            
                var html = dsrte.GetSelection();
                dsrte.PasteHTML( '<a href="'+url+'"'+(tgt ? ' target="'+tgt+'"' : '')+'>'+html+'</a>' );
            }

            panel.slideUp();
        } );

        // signal callback was successfully processed.
        return true;
    };
};

// Register new plugin with dsRTE
dsRTE.RegisterPlugin( new dsRTE_insertLink(), 'link' );

