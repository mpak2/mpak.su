/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Foreground and Background Color Plugin
 */

var dsRTE_color = function() {

    // Remember if we're applying Foreground or Background color
    this.colorcmd = null;

    /**
     * Execute Plugin.
     * Show the hidden panel.
     */
    this.ExecuteCommand = function( dsrte, arguments, panel ) {
    
        this.colorcmd = arguments;
        $( '#'+dsrte.iframe.id+'-color' ).slideToggle();
        return true;
    };

    /**
     * Prepare Plugin.
     * Attach a Click handler on every emoticon and fix hover behaviour in IE.
     */
    this.PrepareCommand = function( dsrte, arguments, panel, $this ) {
    
        var self = this;
        var id = '#'+dsrte.iframe.id+'-color-table';
        var id2 = '#'+dsrte.iframe.id+'-color-value';
        if ( $( id ).attr( 'handled' ) != '1' ) {
            $( id ).attr( 'handled', '1' ).find( 'td' ).mouseover( function() {
                $( id2 ).val( $(this).attr( 'bgcolor' ) );
            }).click(function() {
            
                self.ApplyColor( dsrte, $( id2 ).val() );
                panel.slideUp();
            });

            // Save references for RemoveColor call
            $( id )[0].dsrte = dsrte;
            $( id )[0].panel = panel;
            $( id )[0].plugin = this;
        }

        // signal callback was successfully processed.
        return true;
    };

    /**
     * Set Text or Background color
     *
     * @param String color
     *   Color identifier to be applied to text (bg or fg)
     */
    this.ApplyColor = function( dsrte, color ) {
    
        // IE looses selection when clicking outside the editor
        if( dsrte.iframe.rng )
            dsrte.iframe.rng.select();

        if ( !$.browser.msie )
            dsrte.doc.execCommand( 'useCSS', false, false );
        dsrte.frame.focus();
        dsrte.doc.execCommand( this.colorcmd, false, color );
        if ( !$.browser.msie )
            dsrte.doc.execCommand( 'useCSS', false, true );
        dsrte.frame.focus();
    };

    /**
     * Remove (Reset) Text or Background color.
     */
    this.RemoveColor = function( id ) {
    
        var dsrte = $( '#'+id+'-color-table' )[0].dsrte;
        var color = this.colorcmd == 'forecolor' ? '#000000' : '#ffffff';
        $( '#'+id+'-color-value' ).val( color );
        this.ApplyColor( dsrte, color );
        $( '#' + dsrte.iframe.id + '-color' ).slideUp();
    };

};

// Register new plugin with dsRTE (need to register the same instance for each command1)
var dsRTE_color_temp = new dsRTE_color();
dsRTE.RegisterPlugin( dsRTE_color_temp, 'fgcolor' );
dsRTE.RegisterPlugin( dsRTE_color_temp, 'bgcolor' );

